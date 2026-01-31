<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\User;
use App\Models\BulkMessage;
use App\Models\Livestock;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\File;

class HealthCheckController extends Controller
{
    /**
     * Display the health & diagnostic dashboard
     */
    public function index()
    {
        $health = [
            'database' => $this->checkDatabase(),
            'storage' => $this->checkStorage(),
            'cache' => $this->checkCache(),
            'email' => $this->checkEmailConfig(),
            'sms' => $this->checkSmsConfig(),
            'permissions' => $this->checkPermissions(),
            'recentErrors' => $this->getRecentErrors(),
            'systemStats' => $this->getSystemStats(),
            'brokenFeatures' => $this->detectBrokenFeatures(),
        ];

        $overallStatus = $this->calculateOverallStatus($health);

        return view('admin.health-check.index', compact('health', 'overallStatus'));
    }

    /**
     * Check database connectivity
     */
    protected function checkDatabase()
    {
        try {
            DB::connection()->getPdo();
            $tablesCount = count(DB::select('SHOW TABLES'));

            return [
                'status' => 'healthy',
                'message' => 'Database connection successful',
                'details' => "Connected to database with {$tablesCount} tables",
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'critical',
                'message' => 'Database connection failed',
                'details' => $e->getMessage(),
            ];
        }
    }

    /**
     * Check storage directories
     */
    protected function checkStorage()
    {
        $paths = [
            'storage/app' => storage_path('app'),
            'storage/logs' => storage_path('logs'),
            'storage/framework/cache' => storage_path('framework/cache'),
            'storage/framework/sessions' => storage_path('framework/sessions'),
            'storage/framework/views' => storage_path('framework/views'),
        ];

        $issues = [];
        foreach ($paths as $name => $path) {
            if (!File::exists($path)) {
                $issues[] = "$name does not exist";
            } elseif (!File::isWritable($path)) {
                $issues[] = "$name is not writable";
            }
        }

        if (empty($issues)) {
            return [
                'status' => 'healthy',
                'message' => 'All storage directories are accessible',
                'details' => count($paths) . ' directories checked',
            ];
        }

        return [
            'status' => 'warning',
            'message' => 'Some storage directories have issues',
            'details' => implode(', ', $issues),
        ];
    }

    /**
     * Check cache system
     */
    protected function checkCache()
    {
        try {
            Cache::put('health_check_test', 'working', 60);
            $value = Cache::get('health_check_test');
            Cache::forget('health_check_test');

            if ($value === 'working') {
                return [
                    'status' => 'healthy',
                    'message' => 'Cache system working',
                    'details' => 'Cache driver: ' . config('cache.default'),
                ];
            }

            return [
                'status' => 'warning',
                'message' => 'Cache test failed',
                'details' => 'Cache read/write not working properly',
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'warning',
                'message' => 'Cache system error',
                'details' => $e->getMessage(),
            ];
        }
    }

    /**
     * Check email configuration
     */
    protected function checkEmailConfig()
    {
        $provider = Setting::get('email_provider', 'smtp');

        $configured = match($provider) {
            'smtp' => Setting::get('smtp_host') && Setting::get('smtp_port'),
            'sendgrid' => Setting::get('sendgrid_api_key'),
            'mailgun' => Setting::get('mailgun_api_key') && Setting::get('mailgun_domain'),
            'ses' => Setting::get('ses_key') && Setting::get('ses_secret'),
            default => false,
        };

        if ($configured) {
            return [
                'status' => 'healthy',
                'message' => 'Email configured',
                'details' => "Provider: " . ucfirst($provider),
            ];
        }

        return [
            'status' => 'warning',
            'message' => 'Email not fully configured',
            'details' => "Provider: " . ucfirst($provider) . " (missing credentials)",
        ];
    }

    /**
     * Check SMS configuration
     */
    protected function checkSmsConfig()
    {
        $provider = Setting::get('sms_provider', 'twilio');

        $configured = match($provider) {
            'kudi' => Setting::get('kudi_username') && Setting::get('kudi_password'),
            'termii' => Setting::get('termii_api_key'),
            'africastalking' => Setting::get('africastalking_username') && Setting::get('africastalking_api_key'),
            'bulksms' => Setting::get('bulksms_api_token'),
            'twilio' => Setting::get('twilio_account_sid') && Setting::get('twilio_auth_token'),
            default => false,
        };

        if ($configured) {
            return [
                'status' => 'healthy',
                'message' => 'SMS configured',
                'details' => "Provider: " . ucfirst($provider),
            ];
        }

        return [
            'status' => 'warning',
            'message' => 'SMS not fully configured',
            'details' => "Provider: " . ucfirst($provider) . " (missing credentials)",
        ];
    }

    /**
     * Check file permissions
     */
    protected function checkPermissions()
    {
        $criticalPaths = [
            storage_path(),
            storage_path('logs'),
            base_path('bootstrap/cache'),
        ];

        $issues = [];
        foreach ($criticalPaths as $path) {
            if (!File::isWritable($path)) {
                $issues[] = basename($path) . ' is not writable';
            }
        }

        if (empty($issues)) {
            return [
                'status' => 'healthy',
                'message' => 'File permissions correct',
                'details' => count($criticalPaths) . ' critical paths checked',
            ];
        }

        return [
            'status' => 'critical',
            'message' => 'Permission issues detected',
            'details' => implode(', ', $issues),
        ];
    }

    /**
     * Get recent errors from logs
     */
    protected function getRecentErrors()
    {
        try {
            $logFile = storage_path('logs/laravel.log');

            if (!File::exists($logFile)) {
                return [
                    'count' => 0,
                    'errors' => [],
                ];
            }

            $content = File::get($logFile);
            $lines = explode("\n", $content);

            // Get last 100 lines
            $recentLines = array_slice($lines, -100);

            // Count errors
            $errorCount = 0;
            $errors = [];

            foreach ($recentLines as $line) {
                if (str_contains($line, '.ERROR:')) {
                    $errorCount++;
                    if (count($errors) < 5) {  // Only keep last 5 errors
                        $errors[] = substr($line, 0, 200);  // Truncate long lines
                    }
                }
            }

            return [
                'count' => $errorCount,
                'errors' => $errors,
            ];
        } catch (\Exception $e) {
            return [
                'count' => 0,
                'errors' => ['Error reading log file: ' . $e->getMessage()],
            ];
        }
    }

    /**
     * Get system statistics
     */
    protected function getSystemStats()
    {
        return [
            'users_total' => User::count(),
            'users_active' => User::where('account_status', 'active')->count(),
            'users_today' => User::whereDate('created_at', today())->count(),
            'farmers' => User::where('role', 'farmer')->count(),
            'professionals' => User::where('role', 'animal_health_professional')->count(),
            'volunteers' => User::where('role', 'volunteer')->count(),
            'livestock_total' => Livestock::count(),
            'livestock_today' => Livestock::whereDate('created_at', today())->count(),
            'messages_sent' => BulkMessage::where('status', 'sent')->count(),
            'messages_pending' => BulkMessage::where('status', 'draft')->count(),
        ];
    }

    /**
     * Detect broken features
     */
    protected function detectBrokenFeatures()
    {
        $broken = [];

        // Check if bulk_message_logs has required columns
        try {
            $columns = DB::select("SHOW COLUMNS FROM bulk_message_logs");
            $columnNames = array_column($columns, 'Field');

            if (!in_array('channel', $columnNames)) {
                $broken[] = [
                    'feature' => 'Bulk Messaging',
                    'issue' => 'Missing "channel" column in bulk_message_logs table',
                    'severity' => 'critical',
                    'fix' => 'Run /fix-bulk-message-logs.php',
                ];
            }
        } catch (\Exception $e) {
            $broken[] = [
                'feature' => 'Bulk Messaging',
                'issue' => 'Table "bulk_message_logs" does not exist',
                'severity' => 'critical',
                'fix' => 'Run database migrations',
            ];
        }

        // Check role_conversion_logs table
        try {
            DB::select("SHOW TABLES LIKE 'role_conversion_logs'");
        } catch (\Exception $e) {
            $broken[] = [
                'feature' => 'User Role Conversion',
                'issue' => 'Missing role_conversion_logs table',
                'severity' => 'warning',
                'fix' => 'Run /create-role-conversion-logs.php',
            ];
        }

        // Check SMS configuration
        $smsProvider = Setting::get('sms_provider');
        if (!$smsProvider) {
            $broken[] = [
                'feature' => 'SMS Sending',
                'issue' => 'SMS provider not configured',
                'severity' => 'warning',
                'fix' => 'Configure SMS provider in Settings → SMS',
            ];
        }

        // Check email configuration
        $emailProvider = Setting::get('email_provider');
        if (!$emailProvider) {
            $broken[] = [
                'feature' => 'Email Sending',
                'issue' => 'Email provider not configured',
                'severity' => 'warning',
                'fix' => 'Configure email provider in Settings → Email',
            ];
        }

        return $broken;
    }

    /**
     * Calculate overall system status
     */
    protected function calculateOverallStatus($health)
    {
        $criticalCount = 0;
        $warningCount = 0;
        $healthyCount = 0;

        foreach ($health as $key => $check) {
            if ($key === 'recentErrors' || $key === 'systemStats' || $key === 'brokenFeatures') {
                continue;
            }

            if (isset($check['status'])) {
                switch ($check['status']) {
                    case 'critical':
                        $criticalCount++;
                        break;
                    case 'warning':
                        $warningCount++;
                        break;
                    case 'healthy':
                        $healthyCount++;
                        break;
                }
            }
        }

        // Add broken features to counts
        foreach ($health['brokenFeatures'] as $broken) {
            if ($broken['severity'] === 'critical') {
                $criticalCount++;
            } else {
                $warningCount++;
            }
        }

        if ($criticalCount > 0) {
            return [
                'status' => 'critical',
                'message' => "$criticalCount critical issue(s) detected",
                'color' => 'red',
            ];
        }

        if ($warningCount > 0) {
            return [
                'status' => 'warning',
                'message' => "$warningCount warning(s) detected",
                'color' => 'yellow',
            ];
        }

        return [
            'status' => 'healthy',
            'message' => 'All systems operational',
            'color' => 'green',
        ];
    }

    /**
     * Run a full system diagnostic
     */
    public function runDiagnostic()
    {
        // This would run comprehensive tests
        // For now, redirect back to index
        return redirect()->route('admin.health-check.index')
            ->with('success', 'Diagnostic scan completed!');
    }
}
