<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Setting;

class SettingsSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // General Settings
            ['key' => 'site_name', 'value' => 'FarmVax', 'type' => 'string', 'group' => 'general', 'description' => 'Website name'],
            ['key' => 'site_description', 'value' => 'Real-time vaccine and outbreak notifications for farmers', 'type' => 'string', 'group' => 'general', 'description' => 'Site description'],
            ['key' => 'site_logo', 'value' => '/images/logo.png', 'type' => 'image', 'group' => 'general', 'description' => 'Site logo path'],
            ['key' => 'site_favicon', 'value' => '/images/favicon.ico', 'type' => 'image', 'group' => 'general', 'description' => 'Site favicon path'],
            ['key' => 'contact_email', 'value' => 'info@farmvax.com', 'type' => 'string', 'group' => 'general', 'description' => 'Contact email'],
            ['key' => 'contact_phone', 'value' => '+234 800 000 0000', 'type' => 'string', 'group' => 'general', 'description' => 'Contact phone'],

            // Email Settings
            ['key' => 'email_provider', 'value' => 'smtp', 'type' => 'string', 'group' => 'email', 'description' => 'Email provider (smtp, sendgrid, mailgun, ses)'],
            ['key' => 'smtp_host', 'value' => 'smtp.mailtrap.io', 'type' => 'string', 'group' => 'email', 'description' => 'SMTP host'],
            ['key' => 'smtp_port', 'value' => '587', 'type' => 'string', 'group' => 'email', 'description' => 'SMTP port'],
            ['key' => 'smtp_username', 'value' => '', 'type' => 'string', 'group' => 'email', 'description' => 'SMTP username'],
            ['key' => 'smtp_password', 'value' => '', 'type' => 'string', 'group' => 'email', 'description' => 'SMTP password'],
            ['key' => 'smtp_encryption', 'value' => 'tls', 'type' => 'string', 'group' => 'email', 'description' => 'SMTP encryption'],
            ['key' => 'from_email', 'value' => 'noreply@farmvax.com', 'type' => 'string', 'group' => 'email', 'description' => 'From email address'],
            ['key' => 'from_name', 'value' => 'FarmVax', 'type' => 'string', 'group' => 'email', 'description' => 'From name'],

            // SendGrid Settings
            ['key' => 'sendgrid_api_key', 'value' => '', 'type' => 'string', 'group' => 'email', 'description' => 'SendGrid API key'],

            // Mailgun Settings
            ['key' => 'mailgun_domain', 'value' => '', 'type' => 'string', 'group' => 'email', 'description' => 'Mailgun domain'],
            ['key' => 'mailgun_api_key', 'value' => '', 'type' => 'string', 'group' => 'email', 'description' => 'Mailgun API key'],

            // Amazon SES Settings
            ['key' => 'ses_key', 'value' => '', 'type' => 'string', 'group' => 'email', 'description' => 'Amazon SES access key'],
            ['key' => 'ses_secret', 'value' => '', 'type' => 'string', 'group' => 'email', 'description' => 'Amazon SES secret key'],
            ['key' => 'ses_region', 'value' => 'us-east-1', 'type' => 'string', 'group' => 'email', 'description' => 'Amazon SES region'],

            // SMS Settings
            ['key' => 'sms_provider', 'value' => 'twilio', 'type' => 'string', 'group' => 'sms', 'description' => 'SMS provider (twilio, kudi, termii, africastalking, bulksms)'],
            ['key' => 'sms_api_key', 'value' => '', 'type' => 'string', 'group' => 'sms', 'description' => 'SMS API key'],
            ['key' => 'sms_api_secret', 'value' => '', 'type' => 'string', 'group' => 'sms', 'description' => 'SMS API secret'],
            ['key' => 'sms_sender_id', 'value' => 'FarmVax', 'type' => 'string', 'group' => 'sms', 'description' => 'SMS sender ID'],
            ['key' => 'sms_from_number', 'value' => '', 'type' => 'string', 'group' => 'sms', 'description' => 'SMS from number (for Twilio)'],

            // AI Settings
            ['key' => 'ai_enabled', 'value' => '0', 'type' => 'boolean', 'group' => 'ai', 'description' => 'Enable AI chatbot'],
            ['key' => 'ai_provider', 'value' => 'openai', 'type' => 'string', 'group' => 'ai', 'description' => 'AI provider (openai, anthropic, etc.)'],
            ['key' => 'ai_api_key', 'value' => '', 'type' => 'string', 'group' => 'ai', 'description' => 'AI API key'],
            ['key' => 'ai_model', 'value' => 'gpt-3.5-turbo', 'type' => 'string', 'group' => 'ai', 'description' => 'AI model to use'],
            ['key' => 'ai_temperature', 'value' => '0.7', 'type' => 'string', 'group' => 'ai', 'description' => 'AI temperature setting'],
            ['key' => 'ai_max_tokens', 'value' => '500', 'type' => 'string', 'group' => 'ai', 'description' => 'Maximum tokens per response'],

            // Site Builder
            ['key' => 'hero_title', 'value' => 'Protect Your Livestock with Smart Technology', 'type' => 'string', 'group' => 'site', 'description' => 'Hero section title'],
            ['key' => 'hero_subtitle', 'value' => 'Real-time vaccine and outbreak notifications for farmers and veterinary professionals', 'type' => 'string', 'group' => 'site', 'description' => 'Hero section subtitle'],
            ['key' => 'hero_image', 'value' => '/images/hero.jpg', 'type' => 'image', 'group' => 'site', 'description' => 'Hero section image'],

            // Feature Flags
            ['key' => 'enable_chatbot', 'value' => '1', 'type' => 'boolean', 'group' => 'features', 'description' => 'Enable chatbot feature'],
            ['key' => 'enable_live_chat', 'value' => '1', 'type' => 'boolean', 'group' => 'features', 'description' => 'Enable live chat'],
            ['key' => 'enable_ads', 'value' => '1', 'type' => 'boolean', 'group' => 'features', 'description' => 'Enable advertisements'],
            ['key' => 'enable_outbreak_alerts', 'value' => '1', 'type' => 'boolean', 'group' => 'features', 'description' => 'Enable outbreak alerts'],
        ];

        foreach ($settings as $setting) {
            Setting::create($setting);
        }
    }
}
