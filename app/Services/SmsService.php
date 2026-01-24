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
        $this->config = [
            'api_key' => Setting::get('sms_api_key'),
            'api_secret' => Setting::get('sms_api_secret'),
            'sender_id' => Setting::get('sms_sender_id'),
            'from_number' => Setting::get('sms_from_number'),
        ];
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
            // Twilio implementation
            // Install: composer require twilio/sdk
            /*
            $twilio = new \Twilio\Rest\Client(
                $this->config['api_key'],
                $this->config['api_secret']
            );

            $result = $twilio->messages->create($to, [
                'from' => $this->config['from_number'],
                'body' => $message
            ]);

            return [
                'success' => true,
                'message_id' => $result->sid,
                'status' => $result->status
            ];
            */

            // Mock response for now
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
            // Kudi SMS API implementation
            $url = 'https://api.kudisms.net/api/v1/send';

            $data = [
                'api_key' => $this->config['api_key'],
                'sender_id' => $this->config['sender_id'],
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

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            $result = json_decode($response, true);

            if ($httpCode === 200 && isset($result['status']) && $result['status'] === 'success') {
                return [
                    'success' => true,
                    'message_id' => $result['message_id'] ?? 'kudi_' . uniqid(),
                    'provider' => 'kudi'
                ];
            }

            return [
                'success' => false,
                'error' => $result['message'] ?? 'Unknown error'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    protected function sendViaTermii(string $to, string $message): array
    {
        try {
            // Termii API implementation
            $url = 'https://api.ng.termii.com/api/sms/send';

            $data = [
                'api_key' => $this->config['api_key'],
                'to' => $to,
                'from' => $this->config['sender_id'],
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

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            $result = json_decode($response, true);

            if ($httpCode === 200) {
                return [
                    'success' => true,
                    'message_id' => $result['message_id'] ?? 'termii_' . uniqid(),
                    'provider' => 'termii'
                ];
            }

            return [
                'success' => false,
                'error' => $result['message'] ?? 'Unknown error'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    protected function sendViaAfricasTalking(string $to, string $message): array
    {
        try {
            // Africa's Talking implementation
            $url = 'https://api.africastalking.com/version1/messaging';

            $data = [
                'username' => $this->config['api_key'],
                'to' => $to,
                'message' => $message,
                'from' => $this->config['sender_id']
            ];

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'apiKey: ' . $this->config['api_secret'],
                'Content-Type: application/x-www-form-urlencoded'
            ]);

            $response = curl_exec($ch);
            curl_close($ch);

            $result = json_decode($response, true);

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
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    protected function sendViaBulkSMS(string $to, string $message): array
    {
        try {
            // BulkSMS Nigeria implementation
            $url = 'https://www.bulksmsnigeria.com/api/v1/sms/create';

            $data = [
                'api_token' => $this->config['api_key'],
                'from' => $this->config['sender_id'],
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

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);

            $result = json_decode($response, true);

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
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }
}
