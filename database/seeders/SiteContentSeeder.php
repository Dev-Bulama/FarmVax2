<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\SiteContent;

class SiteContentSeeder extends Seeder
{
    public function run(): void
    {
        $contents = [
            [
                'section' => 'hero',
                'title' => 'Protect Your Livestock with Smart Technology',
                'subtitle' => 'Real-time vaccine and outbreak notifications for farmers and veterinary professionals',
                'content' => 'Join thousands of farmers and veterinary professionals using FarmVax to keep their livestock healthy and productive.',
                'image' => '/images/hero-bg.jpg',
                'metadata' => json_encode(['cta_text' => 'Get Started', 'cta_link' => '/register']),
                'order' => 1,
                'is_active' => true
            ],
            [
                'section' => 'features',
                'title' => 'Everything You Need to Manage Livestock Health',
                'subtitle' => 'Comprehensive tools for modern farming',
                'content' => json_encode([
                    [
                        'icon' => 'bell',
                        'title' => 'Real-time Outbreak Alerts',
                        'description' => 'Get instant notifications about disease outbreaks in your area'
                    ],
                    [
                        'icon' => 'syringe',
                        'title' => 'Vaccination Reminders',
                        'description' => 'Never miss a vaccination schedule with automated reminders'
                    ],
                    [
                        'icon' => 'clipboard',
                        'title' => 'Digital Health Records',
                        'description' => 'Keep all your livestock health records in one place'
                    ],
                    [
                        'icon' => 'users',
                        'title' => 'Expert Veterinary Access',
                        'description' => 'Connect with certified veterinary professionals instantly'
                    ]
                ]),
                'order' => 2,
                'is_active' => true
            ],
            [
                'section' => 'about',
                'title' => 'About FarmVax',
                'subtitle' => 'Revolutionizing livestock health management',
                'content' => 'FarmVax is a comprehensive platform designed to bridge the gap between farmers and veterinary professionals. We provide real-time information, automated alerts, and digital tools to ensure your livestock stay healthy and productive.',
                'order' => 3,
                'is_active' => true
            ],
            [
                'section' => 'stats',
                'title' => 'Our Impact',
                'content' => json_encode([
                    ['number' => '10,000+', 'label' => 'Farmers Registered'],
                    ['number' => '500+', 'label' => 'Veterinary Professionals'],
                    ['number' => '50,000+', 'label' => 'Animals Protected'],
                    ['number' => '36', 'label' => 'States Covered']
                ]),
                'order' => 4,
                'is_active' => true
            ]
        ];

        foreach ($contents as $content) {
            SiteContent::create($content);
        }
    }
}
