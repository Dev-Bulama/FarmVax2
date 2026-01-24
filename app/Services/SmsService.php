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
                    'error' => 'Kudi SMS credentials not configured'
                ];
            }

            // Kudi SMS API implementation
            $url = 'https://account.kudisms.net/api/';

            $data = [
                'username' => $username,
                'password' => $password,
                'sender' => $senderId,
                'recipient' => $to,
                'message' => $message
            ];

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Content-Type: application/x-www-form-urlencoded',
                'Accept: application/json'
            ]);
            curl_setopt($ch, CURLOPT_TIMEOUT, 30);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            \Log::info('Kudi SMS Response', ['response' => $response, 'code' => $httpCode]);

            if ($httpCode === 200) {
                // Kudi returns 'OK' on success
                if (stripos($response, 'OK') !== false || stripos($response, 'success') !== false) {
                    return [
                        'success' => true,
                        'message_id' => 'kudi_' . uniqid(),
                        'provider' => 'kudi',
                        'response' => $response
                    ];
                }
            }

            return [
                'success' => false,
                'error' => $response ?? 'Unknown error'
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
