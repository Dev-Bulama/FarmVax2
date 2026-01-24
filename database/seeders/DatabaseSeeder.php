<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->command->info('🌱 Starting comprehensive database seeding...');
        $this->command->newLine();

        // Seed in correct order (locations first, then dependencies)
        $this->call([
            LocationSeeder::class,
            ProfessionalDataSeeder::class,
            SettingsSeeder::class,
            SiteContentSeeder::class,
            DummyUsersSeeder::class,
        ]);

        // ========================================
        // SUMMARY
        // ========================================

        $this->command->newLine();
        $this->command->info('========================================');
        $this->command->info('   DATABASE SEEDED SUCCESSFULLY!');
        $this->command->info('========================================');
        $this->command->newLine();
        $this->command->info('📧 LOGIN CREDENTIALS:');
        $this->command->newLine();
        $this->command->info('ADMIN:');
        $this->command->info('  Email: admin@farmvax.com');
        $this->command->info('  Password: admin123');
        $this->command->newLine();
        $this->command->info('FARMERS:');
        $this->command->info('  Email: farmer1@farmvax.com');
        $this->command->info('  Password: farmer123');
        $this->command->info('  --');
        $this->command->info('  Email: farmer2@farmvax.com');
        $this->command->info('  Password: farmer123');
        $this->command->newLine();
        $this->command->info('PROFESSIONALS:');
        $this->command->info('  Email: professional1@farmvax.com (Approved)');
        $this->command->info('  Password: professional123');
        $this->command->info('  --');
        $this->command->info('  Email: professional3@farmvax.com (Pending)');
        $this->command->info('  Password: professional123');
        $this->command->newLine();
        $this->command->info('VOLUNTEERS:');
        $this->command->info('  Email: volunteer1@farmvax.com');
        $this->command->info('  Password: volunteer123');
        $this->command->newLine();
        $this->command->info('========================================');
        $this->command->info('✅ All systems ready!');
        $this->command->info('========================================');
    }
}
