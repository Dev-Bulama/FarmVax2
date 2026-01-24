<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Mail;

class EmailService
{
    protected $provider;
    protected $config;

    public function __construct()
    {
        $this->provider = Setting::get('email_provider', 'smtp');
        $this->loadConfig();
    }

    protected function loadConfig()
    {
        $this->config = [
            // SMTP
            'smtp_host' => Setting::get('smtp_host'),
            'smtp_port' => Setting::get('smtp_port'),
            'smtp_username' => Setting::get('smtp_username'),
            'smtp_password' => Setting::get('smtp_password'),
            'smtp_encryption' => Setting::get('smtp_encryption'),
            'from_email' => Setting::get('from_email'),
            'from_name' => Setting::get('from_name'),

            // SendGrid
            'sendgrid_api_key' => Setting::get('sendgrid_api_key'),

            // Mailgun
            'mailgun_domain' => Setting::get('mailgun_domain'),
            'mailgun_api_key' => Setting::get('mailgun_api_key'),

            // Amazon SES
            'ses_key' => Setting::get('ses_key'),
            'ses_secret' => Setting::get('ses_secret'),
            'ses_region' => Setting::get('ses_region'),
        ];
    }

    public function send(string $to, string $subject, string $message, array $data = []): array
    {
        try {
            // Use Laravel's Mail facade with dynamic configuration
            $this->configureMail();

            Mail::send([], [], function ($mail) use ($to, $subject, $message) {
                $mail->to($to)
                    ->subject($subject)
                    ->html($message);
            });

            return [
                'success' => true,
                'provider' => $this->provider
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'error' => $e->getMessage()
            ];
        }
    }

    protected function configureMail()
    {
        match($this->provider) {
            'smtp' => $this->configureSmtp(),
            'sendgrid' => $this->configureSendGrid(),
            'mailgun' => $this->configureMailgun(),
            'ses' => $this->configureSes(),
            default => null
        };
    }

    protected function configureSmtp()
    {
        config([
            'mail.mailers.smtp.host' => $this->config['smtp_host'],
            'mail.mailers.smtp.port' => $this->config['smtp_port'],
            'mail.mailers.smtp.username' => $this->config['smtp_username'],
            'mail.mailers.smtp.password' => $this->config['smtp_password'],
            'mail.mailers.smtp.encryption' => $this->config['smtp_encryption'],
            'mail.from.address' => $this->config['from_email'],
            'mail.from.name' => $this->config['from_name'],
        ]);
    }

    protected function configureSendGrid()
    {
        config([
            'mail.default' => 'sendgrid',
            'services.sendgrid.api_key' => $this->config['sendgrid_api_key'],
            'mail.from.address' => $this->config['from_email'],
            'mail.from.name' => $this->config['from_name'],
        ]);
    }

    protected function configureMailgun()
    {
        config([
            'mail.default' => 'mailgun',
            'services.mailgun.domain' => $this->config['mailgun_domain'],
            'services.mailgun.secret' => $this->config['mailgun_api_key'],
            'mail.from.address' => $this->config['from_email'],
            'mail.from.name' => $this->config['from_name'],
        ]);
    }

    protected function configureSes()
    {
        config([
            'mail.default' => 'ses',
            'services.ses.key' => $this->config['ses_key'],
            'services.ses.secret' => $this->config['ses_secret'],
            'services.ses.region' => $this->config['ses_region'],
            'mail.from.address' => $this->config['from_email'],
            'mail.from.name' => $this->config['from_name'],
        ]);
    }
}
