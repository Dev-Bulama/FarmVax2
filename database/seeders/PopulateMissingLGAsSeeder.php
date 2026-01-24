<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PopulateMissingLGAsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Nigeria - Add missing LGAs for any states that don't have them
        $nigeriaId = DB::table('countries')->where('code', 'NG')->value('id');
        
        if (!$nigeriaId) {
            $this->command->error('Nigeria not found in countries table!');
            return;
        }
        
        // Get all Nigerian states
        $states = DB::table('states')->where('country_id', $nigeriaId)->get();
        
        foreach ($states as $state) {
            $lgaCount = DB::table('lgas')->where('state_id', $state->id)->count();
            
            if ($lgaCount == 0) {
                $this->command->warn("State '{$state->name}' has no LGAs. Adding default LGA...");
                
                // Add a default "Central" LGA for states without data
                DB::table('lgas')->insert([
                    'state_id' => $state->id,
                    'name' => "{$state->name} Central",
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);
                
                $this->command->info("Added '{$state->name} Central' LGA");
            }
        }
        
        // Liberia - Add LGAs for Liberian counties
        $liberiaId = DB::table('countries')->where('code', 'LR')->value('id');
        
        if ($liberiaId) {
            $liberianCounties = DB::table('states')->where('country_id', $liberiaId)->get();
            
            foreach ($liberianCounties as $county) {
                $lgaCount = DB::table('lgas')->where('state_id', $county->id)->count();
                
                if ($lgaCount == 0) {
                    $this->command->warn("County '{$county->name}' has no districts. Adding default...");
                    
                    DB::table('lgas')->insert([
                        'state_id' => $county->id,
                        'name' => "{$county->name} District 1",
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                    
                    $this->command->info("Added '{$county->name} District 1'");
                }
            }
        }
        
        $this->command->info('✓ Finished populating missing LGAs');
    }
}
