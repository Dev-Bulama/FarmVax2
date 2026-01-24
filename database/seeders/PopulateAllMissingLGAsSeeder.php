<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PopulateAllMissingLGAsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $nigeriaId = DB::table('countries')->where('code', 'NG')->value('id');
        $liberiaId = DB::table('countries')->where('code', 'LR')->value('id');
        
        // ============================================
        // NIGERIA - Complete LGA Data
        // ============================================
        
        $nigeriaLGAs = [
            // Katsina State (ID: 21)
            'Katsina' => [
                'Bakori', 'Batagarawa', 'Batsari', 'Baure', 'Bindawa', 'Charanchi', 'Dan Musa', 
                'Dandume', 'Danja', 'Daura', 'Dutsi', 'Dutsin-Ma', 'Faskari', 'Funtua', 'Ingawa', 
                'Jibia', 'Kafur', 'Kaita', 'Kankara', 'Kankia', 'Katsina', 'Kurfi', 'Kusada', 
                'Mai\'Adua', 'Malumfashi', 'Mani', 'Mashi', 'Matazu', 'Musawa', 'Rimi', 'Sabuwa', 
                'Safana', 'Sandamu', 'Zango'
            ],
            
            // Kebbi State (ID: 22)
            'Kebbi' => [
                'Aleiro', 'Arewa', 'Argungu', 'Augie', 'Bagudo', 'Birnin Kebbi', 'Bunza', 'Dandi', 
                'Fakai', 'Gwandu', 'Jega', 'Kalgo', 'Koko/Besse', 'Maiyama', 'Ngaski', 'Sakaba', 
                'Shanga', 'Suru', 'Wasagu/Danko', 'Yauri', 'Zuru'
            ],
            
            // Kogi State (ID: 23)
            'Kogi' => [
                'Adavi', 'Ajaokuta', 'Ankpa', 'Bassa', 'Dekina', 'Ibaji', 'Idah', 'Igalamela-Odolu', 
                'Ijumu', 'Kabba/Bunu', 'Kogi', 'Lokoja', 'Mopa-Muro', 'Ofu', 'Ogori/Magongo', 
                'Okehi', 'Okene', 'Olamaboro', 'Omala', 'Yagba East', 'Yagba West'
            ],
            
            // Kwara State (ID: 24)
            'Kwara' => [
                'Asa', 'Baruten', 'Edu', 'Ekiti', 'Ifelodun', 'Ilorin East', 'Ilorin South', 
                'Ilorin West', 'Irepodun', 'Isin', 'Kaiama', 'Moro', 'Offa', 'Oke-Ero', 'Oyun', 
                'Pategi'
            ],
            
            // Nasarawa State (ID: 26)
            'Nasarawa' => [
                'Akwanga', 'Awe', 'Doma', 'Karu', 'Keana', 'Keffi', 'Kokona', 'Lafia', 'Nasarawa', 
                'Nasarawa-Eggon', 'Obi', 'Toto', 'Wamba'
            ],
            
            // Niger State (ID: 27)
            'Niger' => [
                'Agaie', 'Agwara', 'Bida', 'Borgu', 'Bosso', 'Chanchaga', 'Edati', 'Gbako', 'Gurara', 
                'Katcha', 'Kontagora', 'Lapai', 'Lavun', 'Magama', 'Mariga', 'Mashegu', 'Mokwa', 
                'Munya', 'Paikoro', 'Rafi', 'Rijau', 'Shiroro', 'Suleja', 'Tafa', 'Wushishi'
            ],
            
            // Ogun State (ID: 28)
            'Ogun' => [
                'Abeokuta North', 'Abeokuta South', 'Ado-Odo/Ota', 'Ewekoro', 'Ifo', 'Ijebu East', 
                'Ijebu North', 'Ijebu North East', 'Ijebu Ode', 'Ikenne', 'Imeko-Afon', 'Ipokia', 
                'Obafemi-Owode', 'Odeda', 'Odogbolu', 'Ogun Waterside', 'Remo North', 'Sagamu', 
                'Yewa North', 'Yewa South'
            ],
            
            // Ondo State (ID: 29)
            'Ondo' => [
                'Akoko North East', 'Akoko North West', 'Akoko South East', 'Akoko South West', 
                'Akure North', 'Akure South', 'Ese-Odo', 'Idanre', 'Ifedore', 'Ilaje', 'Ile-Oluji/Okeigbo', 
                'Irele', 'Odigbo', 'Okitipupa', 'Ondo East', 'Ondo West', 'Ose', 'Owo'
            ],
            
            // Osun State (ID: 30)
            'Osun' => [
                'Aiyedaade', 'Aiyedire', 'Atakumosa East', 'Atakumosa West', 'Boluwaduro', 'Boripe', 
                'Ede North', 'Ede South', 'Egbedore', 'Ejigbo', 'Ife Central', 'Ife East', 'Ife North', 
                'Ife South', 'Ifedayo', 'Ifelodun', 'Ila', 'Ilesa East', 'Ilesa West', 'Irepodun', 
                'Irewole', 'Isokan', 'Iwo', 'Obokun', 'Odo-Otin', 'Ola-Oluwa', 'Olorunda', 'Oriade', 
                'Orolu', 'Osogbo'
            ],
            
            // Plateau State (ID: 32)
            'Plateau' => [
                'Barkin Ladi', 'Bassa', 'Bokkos', 'Jos East', 'Jos North', 'Jos South', 'Kanam', 
                'Kanke', 'Langtang North', 'Langtang South', 'Mangu', 'Mikang', 'Pankshin', 'Qua\'an Pan', 
                'Riyom', 'Shendam', 'Wase'
            ],
            
            // Sokoto State (ID: 34)
            'Sokoto' => [
                'Binji', 'Bodinga', 'Dange-Shuni', 'Gada', 'Goronyo', 'Gudu', 'Gwadabawa', 'Illela', 
                'Isa', 'Kebbe', 'Kware', 'Rabah', 'Sabon Birni', 'Shagari', 'Silame', 'Sokoto North', 
                'Sokoto South', 'Tambuwal', 'Tangaza', 'Tureta', 'Wamako', 'Wurno', 'Yabo'
            ],
            
            // Taraba State (ID: 35)
            'Taraba' => [
                'Ardo-Kola', 'Bali', 'Donga', 'Gashaka', 'Gassol', 'Ibi', 'Jalingo', 'Karim-Lamido', 
                'Kurmi', 'Lau', 'Sardauna', 'Takum', 'Ussa', 'Wukari', 'Yorro', 'Zing'
            ],
            
            // Yobe State (ID: 36)
            'Yobe' => [
                'Bade', 'Bursari', 'Damaturu', 'Fika', 'Fune', 'Geidam', 'Gujba', 'Gulani', 'Jakusko', 
                'Karasuwa', 'Machina', 'Nangere', 'Nguru', 'Potiskum', 'Tarmuwa', 'Yunusari', 'Yusufari'
            ],
            
            // Zamfara State (ID: 37)
            'Zamfara' => [
                'Anka', 'Bakura', 'Birnin Magaji', 'Bukkuyum', 'Bungudu', 'Gummi', 'Gusau', 'Kaura Namoda', 
                'Maradun', 'Maru', 'Shinkafi', 'Talata Mafara', 'Tsafe', 'Zurmi'
            ],
        ];
        
        // Insert Nigerian LGAs
        foreach ($nigeriaLGAs as $stateName => $lgas) {
            $stateId = DB::table('states')
                ->where('country_id', $nigeriaId)
                ->where('name', $stateName)
                ->value('id');
            
            if ($stateId) {
                foreach ($lgas as $lgaName) {
                    DB::table('lgas')->insert([
                        'state_id' => $stateId,
                        'name' => $lgaName,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
                $this->command->info("✓ Added " . count($lgas) . " LGAs to {$stateName} State");
            }
        }
        
        // ============================================
        // LIBERIA - Complete Districts Data
        // ============================================
        
        $liberiaDistricts = [
            'Bomi' => ['Dewoin District', 'Klay District', 'Mecca District', 'Senjeh District'],
            'Bong' => ['Fuamah District', 'Jorquelleh District', 'Kokoyah District', 'Panta-Kpa District', 'Salala District', 'Sanoyea District', 'Suakoko District', 'Tukpahblee District', 'Zota District'],
            'Gbarpolu' => ['Belle Yalla District', 'Bokomu District', 'Gbarma District', 'Kong District'],
            'Grand Bassa' => ['District 1', 'District 2', 'District 3', 'District 4', 'District 5', 'District 6', 'District 7', 'District 8'],
            'Grand Cape Mount' => ['Commonwealth District', 'Garwula District', 'Gola Konneh District', 'Porkpa District', 'Tewor District'],
            'Grand Gedeh' => ['Cavalla District', 'Konobo District', 'Putu District', 'Tchien District'],
            'Grand Kru' => ['Bolloh/Sorkwor District', 'Buah District', 'Forpoh District', 'Jloh District', 'Sasstown District', 'Trehn District'],
            'Lofa' => ['Foya District', 'Kolahun District', 'Salayea District', 'Vahun District', 'Voinjama District', 'Zorzor District'],
            'Margibi' => ['Firestone District', 'Gibi District', 'Kakata District', 'Mambah-Kaba District', 'Todee District'],
            'Maryland' => ['Barrobo District', 'Karluway District', 'Nyorken District', 'Pleebo/Sodeken District'],
            'Nimba' => ['Boe & Quilla District', 'Garr Bain District', 'Gbehlay-Geh District', 'Gbi & Doru District', 'Kparblee District', 'Mah District', 'Saclepea-Mahn District', 'Sanniquellie-Mahn District', 'Twan River District', 'Yarwin District', 'Yarpah Mahn District', 'Zoegeh District'],
            'River Cess' => ['Beh District', 'Central River Cess District', 'Jo River District', 'Norwein District', 'Sam Gbalor District'],
            'River Gee' => ['Chedepo District', 'Gbeapo District', 'Glaro District', 'Karforh District', 'Nyenawliken District', 'Potupo District', 'Sarbo District', 'Tuobo District'],
            'Sinoe' => ['Bokon District', 'Dugbe River District', 'Greenville District', 'Jaedae District', 'Jeadepo District', 'Juarzon District', 'Kpayan District', 'Plahn Nyarohn District', 'Pynes Town District', 'Seekon District', 'Tarjuwon District', 'Wedjah District'],
        ];
        
        // Insert Liberian Districts
        foreach ($liberiaDistricts as $countyName => $districts) {
            $countyId = DB::table('states')
                ->where('country_id', $liberiaId)
                ->where('name', $countyName)
                ->value('id');
            
            if ($countyId) {
                foreach ($districts as $districtName) {
                    DB::table('lgas')->insert([
                        'state_id' => $countyId,
                        'name' => $districtName,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }
                $this->command->info("✓ Added " . count($districts) . " districts to {$countyName} County");
            }
        }
        
        $this->command->info('');
        $this->command->info('========================================');
        $this->command->info('✓✓✓ ALL MISSING LGAs POPULATED! ✓✓✓');
        $this->command->info('========================================');
    }
}