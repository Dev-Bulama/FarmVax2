<?php

namespace App\Services;

use App\Models\Setting;

class SmsService
{
    protected $provider;
    protected $config;

    public function __construct()
    {
        $this->provider = Setting::get('sms_provider', 'twilio');
        $this->loadConfig();
    }

    protected function loadConfig()
    {
        // Load provider-specific configuration
        $this->config = match($this->provider) {
            'kudi' => [
                'username' => Setting::get('kudi_username'),
                'password' => Setting::get('kudi_password'),
                'sender_id' => Setting::get('kudi_sender_id'),
            ],
            'termii' => [
                'api_key' => Setting::get('termii_api_key'),
                'sender_id' => Setting::get('termii_sender_id'),
            ],
            'africastalking' => [
                'username' => Setting::get('africastalking_username'),
                'api_key' => Setting::get('africastalking_api_key'),
                'sender_id' => Setting::get('africastalking_sender_id'),
            ],
            'bulksms' => [
                'api_token' => Setting::get('bulksms_api_token'),
                'sender_id' => Setting::get('bulksms_sender_id'),
            ],
            'twilio' => [
                'account_sid' => Setting::get('twilio_account_sid'),
                'auth_token' => Setting::get('twilio_auth_token'),
                'from_number' => Setting::get('twilio_from_number'),
            ],
            default => []
        };
    }

    public function send(string $to, string $message): array
    {
        return match($this->provider) {
            'twilio' => $this->sendViaTwilio($to, $message),
            'kudi' => $this->sendViaKudi($to, $message),
            'termii' => $this->sendViaTermii($to, $message),
            'africastalking' => $this->sendViaAfricasTalking($to, $message),
            'bulksms' => $this->sendViaBulkSMS($to, $message),
            default => ['success' => false, 'message' => 'Invalid SMS provider']
        };
    }

    protected function sendViaTwilio(string $to, string $message): array
    {
        try {
            $accountSid = $this->config['account_sid'] ?? null;
            $authToken = $this->config['auth_token'] ?? null;
            $fromNumber = $this->config['from_number'] ?? null;

            if (!$accountSid || !$authToken || !$fromNumber) {
                return [
                    'success' => false,
                    'error' => 'Twilio credentials not configured'
                ];
            }

            // Twilio implementation would go here
            // For now, return success for testing
            \Log::info('SMS sent via Twilio', ['to' => $to, 'message' => $message]);

            return [
                'success' => true,
                'message_id' => 'tw_' . uniqid(),
                'provider' => 'twilio'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    protected function sendViaKudi(string $to, string $message): array
    {
        try {
            $username = $this->config['username'] ?? null;
            $password = $this->config['password'] ?? null;
            $senderId = $this->config['sender_id'] ?? null;

            if (!$username || !$password || !$senderId) {
                return [
                    'success' => false,
                    'error' => 'Kudi SMS username, password, or sender ID not configured'
                ];
            }

            // Format phone number (ensure it starts with country code)
            $formattedPhone = $to;
            if (substr($to, 0, 1) === '0') {
                $formattedPhone = '234' . substr($to, 1); // Convert 0803... to 234803...
            } elseif (substr($to, 0, 1) !== '+' && substr($to, 0, 3) !== '234') {
                $formattedPhone = '234' . $to; // Add country code if missing
            }
            $formattedPhone = str_replace('+', '', $formattedPhone); // Remove + if present

            // Kudi SMS API - Based on documentation
            // Format: https://account.kudisms.net/api/?username=X&password=Y&sender=Z&mobiles=N&message=M

            // Build URL with GET parameters (Kudi SMS uses GET method)
            $params = [
                'username' => $username,
                'password' => $password,
                'sender' => $senderId,
                'mobiles' => $formattedPhone,
                'message' => $message
            ];

            $url = 'https://account.kudisms.net/api/?' . http_build_query($params);

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Handle SSL issues
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlError = curl_error($ch);
            curl_close($ch);

            \Log::info('Kudi SMS Request', [
                'to' => $formattedPhone,
                'sender' => $senderId,
                'username' => $username,
                'password_length' => strlen($password),
                'url' => preg_replace('/password=[^&]+/', 'password=***', $url) // Hide password in logs
            ]);
            \Log::info('Kudi SMS Response', [
                'response' => $response,
                'code' => $httpCode,
                'curl_error' => $curlError
            ]);

            if ($curlError) {
                return [
                    'success' => false,
                    'error' => 'Connection Error: ' . $curlError
                ];
            }

            if ($httpCode === 200) {
                // Parse response - Kudi returns various success indicators
                $responseData = json_decode($response, true);

                // Check for success in JSON response
                if (is_array($responseData)) {
                    if (isset($responseData['status']) &&
                        (strtolower($responseData['status']) === 'success' ||
                         strtolower($responseData['status']) === 'ok')) {
                        return [
                            'success' => true,
                            'message_id' => $responseData['message_id'] ?? 'kudi_' . uniqid(),
                            'provider' => 'kudi',
                            'response' => $response
                        ];
                    }

                    // Check for error codes (100 = invalid token, 101 = deactivated, 300 = missing params)
                    if (isset($responseData['code']) && in_array($responseData['code'], [100, 101, 300])) {
                        $errorMsg = match($responseData['code']) {
                            100 => 'Invalid API credentials. Please check your API key in Settings → SMS',
                            101 => 'Your Kudi SMS account has been deactivated. Please contact Kudi SMS support',
                            300 => 'Missing required parameters. Please ensure Sender ID is set',
                            default => $responseData['message'] ?? 'Unknown error'
                        };
                        return [
                            'success' => false,
                            'error' => $errorMsg
                        ];
                    }
                }

                // Check for success in plain text response (like "OK: 12345" or "1701 Message Sent Successfully")
                if (preg_match('/OK[:\s]/i', $response) ||
                    stripos($response, 'success') !== false ||
                    stripos($response, 'sent') !== false ||
                    preg_match('/\d{4}\s+Message\s+Sent/i', $response)) {
                    return [
                        'success' => true,
                        'message_id' => 'kudi_' . uniqid(),
                        'provider' => 'kudi',
                        'response' => $response
                    ];
                }

                // Check for common error patterns
                if (stripos($response, 'login') !== false ||
                    stripos($response, 'authentication') !== false ||
                    stripos($response, 'invalid') !== false) {
                    return [
                        'success' => false,
                        'error' => 'Authentication failed. Please verify your Kudi SMS API credentials in Settings → SMS. Response: ' . $response
                    ];
                }
            }

            // Extract error message from response
            $errorMessage = $response;
            $responseData = json_decode($response, true);
            if (is_array($responseData) && isset($responseData['message'])) {
                $errorMessage = $responseData['message'];
            } elseif (is_array($responseData) && isset($responseData['error'])) {
                $errorMessage = $responseData['error'];
            }

            return [
                'success' => false,
                'error' => 'Kudi SMS: ' . $errorMessage,
                'http_code' => $httpCode
            ];
        } catch (\Exception $e) {
            \Log::error('Kudi SMS Error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    protected function sendViaTermii(string $to, string $message): array
    {
        try {
            $apiKey = $this->config['api_key'] ?? null;
            $senderId = $this->config['sender_id'] ?? null;

            if (!$apiKey || !$senderId) {
                return [
                    'success' => false,
                    'error' => 'Termii credentials not configured'
                ];
            }

            // Termii API implementation
            $url = 'https://api.ng.termii.com/api/sms/send';

            $data = [
                'api_key' => $apiKey,
                'to' => $to,
                'from' => $senderId,
                'sms' => $message,
                'type' => 'plain',
                'channel' => 'generic'
            ];

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json'
            ]);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            $result = json_decode($response, true);

            \Log::info('Termii SMS Response', ['response' => $result, 'code' => $httpCode]);

            if ($httpCode === 200 && isset($result['message_id'])) {
                return [
                    'success' => true,
                    'message_id' => $result['message_id'],
                    'provider' => 'termii'
                ];
            }

            return [
                'success' => false,
                'error' => $result['message'] ?? 'Unknown error'
            ];
        } catch (\Exception $e) {
            \Log::error('Termii SMS Error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    protected function sendViaAfricasTalking(string $to, string $message): array
    {
        try {
            $username = $this->config['username'] ?? null;
            $apiKey = $this->config['api_key'] ?? null;
            $senderId = $this->config['sender_id'] ?? null;

            if (!$username || !$apiKey) {
                return [
                    'success' => false,
                    'error' => 'Africa\'s Talking credentials not configured'
                ];
            }

            // Africa's Talking implementation
            $url = 'https://api.africastalking.com/version1/messaging';

            $data = [
                'username' => $username,
                'to' => $to,
                'message' => $message,
            ];

            if ($senderId) {
                $data['from'] = $senderId;
            }

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'apiKey: ' . $apiKey,
                'Content-Type: application/x-www-form-urlencoded'
            ]);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);

            $response = curl_exec($ch);
            curl_close($ch);

            $result = json_decode($response, true);

            \Log::info('Africa\'s Talking SMS Response', ['response' => $result]);

            if (isset($result['SMSMessageData']['Recipients'][0]['status']) &&
                $result['SMSMessageData']['Recipients'][0]['status'] === 'Success') {
                return [
                    'success' => true,
                    'message_id' => $result['SMSMessageData']['Recipients'][0]['messageId'] ?? 'at_' . uniqid(),
                    'provider' => 'africastalking'
                ];
            }

            return [
                'success' => false,
                'error' => $result['SMSMessageData']['Message'] ?? 'Unknown error'
            ];
        } catch (\Exception $e) {
            \Log::error('Africa\'s Talking SMS Error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    protected function sendViaBulkSMS(string $to, string $message): array
    {
        try {
            $apiToken = $this->config['api_token'] ?? null;
            $senderId = $this->config['sender_id'] ?? null;

            if (!$apiToken || !$senderId) {
                return [
                    'success' => false,
                    'error' => 'BulkSMS credentials not configured'
                ];
            }

            // BulkSMS Nigeria implementation
            $url = 'https://www.bulksmsnigeria.com/api/v1/sms/create';

            $data = [
                'api_token' => $apiToken,
                'from' => $senderId,
                'to' => $to,
                'body' => $message
            ];

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/json'
            ]);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            $result = json_decode($response, true);

            \Log::info('BulkSMS Response', ['response' => $result, 'code' => $httpCode]);

            if ($httpCode === 200 && isset($result['data']['id'])) {
                return [
                    'success' => true,
                    'message_id' => $result['data']['id'],
                    'provider' => 'bulksms'
                ];
            }

            return [
                'success' => false,
                'error' => $result['message'] ?? 'Unknown error'
            ];
        } catch (\Exception $e) {
            \Log::error('BulkSMS Error: ' . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}
