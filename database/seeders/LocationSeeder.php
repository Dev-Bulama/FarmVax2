<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Country;
use App\Models\State;
use App\Models\Lga;
use Illuminate\Support\Facades\DB;

class LocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Check if countries already exist from world package
        $countriesCount = Country::count();
        
        if ($countriesCount === 0) {
            $this->command->info('No countries found. Please run world package migrations first.');
            $this->command->info('Run: php artisan world:migrate');
            return;
        }

        $this->command->info("Found {$countriesCount} countries in database.");

        // Seed Nigerian locations (complete)
        $this->seedNigerianLocations();
        
        // Seed other major countries
        $this->seedUSALocations();
        $this->seedCanadaLocations();
        $this->seedUKLocations();
        $this->seedGhanaLocations();
        $this->seedSouthAfricaLocations();
        
        // Add more countries as needed
        // $this->seedIndiaLocations();
        // $this->seedBrazilLocations();
        // etc...

        $this->command->info('Location data seeding completed!');
    }

    /**
     * Seed Nigerian states and LGAs (COMPLETE)
     */
    private function seedNigerianLocations()
    {
        $this->command->info('Seeding Nigerian locations...');

        // Find Nigeria
        $nigeria = Country::where('name', 'Nigeria')
            ->orWhere('iso2', 'NG')
            ->orWhere('iso3', 'NGA')
            ->first();

        if (!$nigeria) {
            $this->command->warn('Nigeria not found in countries table.');
            return;
        }

        // COMPLETE Nigerian States with LGAs
        $nigerianStates = [
            'Abia' => [
                'Aba North', 'Aba South', 'Arochukwu', 'Bende', 'Ikwuano', 
                'Isiala Ngwa North', 'Isiala Ngwa South', 'Isuikwuato', 
                'Obi Ngwa', 'Ohafia', 'Osisioma', 'Ugwunagbo', 'Ukwa East', 
                'Ukwa West', 'Umuahia North', 'Umuahia South', 'Umu Nneochi'
            ],
            'Adamawa' => [
                'Demsa', 'Fufure', 'Ganye', 'Gayuk', 'Gombi', 'Grie', 
                'Hong', 'Jada', 'Lamurde', 'Madagali', 'Maiha', 'Mayo Belwa', 
                'Michika', 'Mubi North', 'Mubi South', 'Numan', 'Shelleng', 
                'Song', 'Toungo', 'Yola North', 'Yola South'
            ],
            'Akwa Ibom' => [
                'Abak', 'Eastern Obolo', 'Eket', 'Esit Eket', 'Essien Udim', 
                'Etim Ekpo', 'Etinan', 'Ibeno', 'Ibesikpo Asutan', 'Ibiono-Ibom', 
                'Ika', 'Ikono', 'Ikot Abasi', 'Ikot Ekpene', 'Ini', 
                'Itu', 'Mbo', 'Mkpat-Enin', 'Nsit-Atai', 'Nsit-Ibom', 
                'Nsit-Ubium', 'Obot Akara', 'Okobo', 'Onna', 'Oron', 
                'Oruk Anam', 'Udung-Uko', 'Ukanafun', 'Uruan', 'Urue-Offong/Oruko', 
                'Uyo'
            ],
            'Anambra' => [
                'Aguata', 'Anambra East', 'Anambra West', 'Anaocha', 
                'Awka North', 'Awka South', 'Ayamelum', 'Dunukofia', 
                'Ekwusigo', 'Idemili North', 'Idemili South', 'Ihiala', 
                'Njikoka', 'Nnewi North', 'Nnewi South', 'Ogbaru', 
                'Onitsha North', 'Onitsha South', 'Orumba North', 
                'Orumba South', 'Oyi'
            ],
            'Bauchi' => [
                'Alkaleri', 'Bauchi', 'Bogoro', 'Damban', 'Darazo', 
                'Dass', 'Gamawa', 'Ganjuwa', 'Giade', 'Itas/Gadau', 
                'Jama\'are', 'Katagum', 'Kirfi', 'Misau', 'Ningi', 
                'Shira', 'Tafawa Balewa', 'Toro', 'Warji', 'Zaki'
            ],
            'Bayelsa' => [
                'Brass', 'Ekeremor', 'Kolokuma/Opokuma', 'Nembe', 
                'Ogbia', 'Sagbama', 'Southern Ijaw', 'Yenagoa'
            ],
            'Benue' => [
                'Ado', 'Agatu', 'Apa', 'Buruku', 'Gboko', 'Guma', 
                'Gwer East', 'Gwer West', 'Katsina-Ala', 'Konshisha', 
                'Kwande', 'Logo', 'Makurdi', 'Obi', 'Ogbadibo', 
                'Ohimini', 'Oju', 'Okpokwu', 'Otukpo', 'Tarka', 
                'Ukum', 'Ushongo', 'Vandeikya'
            ],
            'Borno' => [
                'Abadam', 'Askira/Uba', 'Bama', 'Bayo', 'Biu', 
                'Chibok', 'Damboa', 'Dikwa', 'Gubio', 'Guzamala', 
                'Gwoza', 'Hawul', 'Jere', 'Kaga', 'Kala/Balge', 
                'Konduga', 'Kukawa', 'Kwaya Kusar', 'Mafa', 'Magumeri', 
                'Maiduguri', 'Marte', 'Mobbar', 'Monguno', 'Ngala', 
                'Nganzai', 'Shani'
            ],
            'Cross River' => [
                'Abi', 'Akamkpa', 'Akpabuyo', 'Bakassi', 'Bekwarra', 
                'Biase', 'Boki', 'Calabar Municipal', 'Calabar South', 
                'Etung', 'Ikom', 'Obanliku', 'Obubra', 'Obudu', 
                'Odukpani', 'Ogoja', 'Yakuur', 'Yala'
            ],
            'Delta' => [
                'Aniocha North', 'Aniocha South', 'Bomadi', 'Burutu', 
                'Ethiope East', 'Ethiope West', 'Ika North East', 
                'Ika South', 'Isoko North', 'Isoko South', 'Ndokwa East', 
                'Ndokwa West', 'Okpe', 'Oshimili North', 'Oshimili South', 
                'Patani', 'Sapele', 'Udu', 'Ughelli North', 'Ughelli South', 
                'Ukwuani', 'Uvwie', 'Warri North', 'Warri South', 'Warri South West'
            ],
            'Ebonyi' => [
                'Abakaliki', 'Afikpo North', 'Afikpo South', 'Ebonyi', 
                'Ezza North', 'Ezza South', 'Ikwo', 'Ishielu', 
                'Ivo', 'Izzi', 'Ohaozara', 'Ohaukwu', 'Onicha'
            ],
            'Edo' => [
                'Akoko-Edo', 'Egor', 'Esan Central', 'Esan North-East', 
                'Esan South-East', 'Esan West', 'Etsako Central', 
                'Etsako East', 'Etsako West', 'Igueben', 'Ikpoba Okha', 
                'Orhionmwon', 'Oredo', 'Ovia North-East', 'Ovia South-West', 
                'Owan East', 'Owan West', 'Uhunmwonde'
            ],
            'Ekiti' => [
                'Ado Ekiti', 'Efon', 'Ekiti East', 'Ekiti South-West', 
                'Ekiti West', 'Emure', 'Gbonyin', 'Ido Osi', 'Ijero', 
                'Ikere', 'Ikole', 'Ilejemeje', 'Irepodun/Ifelodun', 
                'Ise/Orun', 'Moba', 'Oye'
            ],
            'Enugu' => [
                'Aninri', 'Awgu', 'Enugu East', 'Enugu North', 
                'Enugu South', 'Ezeagu', 'Igbo Etiti', 'Igbo Eze North', 
                'Igbo Eze South', 'Isi Uzo', 'Nkanu East', 'Nkanu West', 
                'Nsukka', 'Oji River', 'Udenu', 'Udi', 'Uzo Uwani'
            ],
            'FCT - Abuja' => [
                'Abaji', 'Bwari', 'Gwagwalada', 'Kuje', 'Kwali', 
                'Municipal Area Council'
            ],
            'Gombe' => [
                'Akko', 'Balanga', 'Billiri', 'Dukku', 'Funakaye', 
                'Gombe', 'Kaltungo', 'Kwami', 'Nafada', 'Shongom', 'Yamaltu/Deba'
            ],
            'Imo' => [
                'Aboh Mbaise', 'Ahiazu Mbaise', 'Ehime Mbano', 
                'Ezinihitte', 'Ideato North', 'Ideato South', 
                'Ihitte/Uboma', 'Ikeduru', 'Isiala Mbano', 'Isu', 
                'Mbaitoli', 'Ngor Okpala', 'Njaba', 'Nkwerre', 
                'Nwangele', 'Obowo', 'Oguta', 'Ohaji/Egbema', 
                'Okigwe', 'Orlu', 'Orsu', 'Oru East', 'Oru West', 
                'Owerri Municipal', 'Owerri North', 'Owerri West', 'Unuimo'
            ],
            'Jigawa' => [
                'Auyo', 'Babura', 'Biriniwa', 'Birnin Kudu', 'Buji', 
                'Dutse', 'Gagarawa', 'Garki', 'Gumel', 'Guri', 
                'Gwaram', 'Gwiwa', 'Hadejia', 'Jahun', 'Kafin Hausa', 
                'Kazaure', 'Kiri Kasama', 'Kiyawa', 'Kaugama', 'Maigatari', 
                'Malam Madori', 'Miga', 'Ringim', 'Roni', 'Sule Tankarkar', 
                'Taura', 'Yankwashi'
            ],
            'Kaduna' => [
                'Birnin Gwari', 'Chikun', 'Giwa', 'Igabi', 'Ikara', 
                'Jaba', 'Jema\'a', 'Kachia', 'Kaduna North', 'Kaduna South', 
                'Kagarko', 'Kajuru', 'Kaura', 'Kauru', 'Kubau', 'Kudan', 
                'Lere', 'Makarfi', 'Sabon Gari', 'Sanga', 'Soba', 
                'Zangon Kataf', 'Zaria'
            ],
            'Kano' => [
                'Ajingi', 'Albasu', 'Bagwai', 'Bebeji', 'Bichi', 
                'Bunkure', 'Dala', 'Dambatta', 'Dawakin Kudu', 'Dawakin Tofa', 
                'Doguwa', 'Fagge', 'Gabasawa', 'Garko', 'Garun Mallam', 
                'Gaya', 'Gezawa', 'Gwale', 'Gwarzo', 'Kabo', 
                'Kano Municipal', 'Karaye', 'Kibiya', 'Kiru', 'Kumbotso', 
                'Kunchi', 'Kura', 'Madobi', 'Makoda', 'Minjibir', 
                'Nasarawa', 'Rano', 'Rimin Gado', 'Rogo', 'Shanono', 
                'Sumaila', 'Takai', 'Tarauni', 'Tofa', 'Tsanyawa', 
                'Tudun Wada', 'Ungogo', 'Warawa', 'Wudil'
            ],
            'Katsina' => [
                'Bakori', 'Batagarawa', 'Batsari', 'Baure', 'Bindawa', 
                'Charanchi', 'Dandume', 'Danja', 'Dan Musa', 'Daura', 
                'Dutsi', 'Dutsin Ma', 'Faskari', 'Funtua', 'Ingawa', 
                'Jibia', 'Kafur', 'Kaita', 'Kankara', 'Kankia', 
                'Katsina', 'Kurfi', 'Kusada', 'Mai\'Adua', 'Malumfashi', 
                'Mani', 'Mashi', 'Matazu', 'Musawa', 'Rimi', 
                'Sabuwa', 'Safana', 'Sandamu', 'Zango'
            ],
            'Kebbi' => [
                'Aleiro', 'Arewa Dandi', 'Argungu', 'Augie', 'Bagudo', 
                'Birnin Kebbi', 'Bunza', 'Dandi', 'Fakai', 'Gwandu', 
                'Jega', 'Kalgo', 'Koko/Besse', 'Maiyama', 'Ngaski', 
                'Sakaba', 'Shanga', 'Suru', 'Danko/Wasagu', 'Yauri', 'Zuru'
            ],
            'Kogi' => [
                'Adavi', 'Ajaokuta', 'Ankpa', 'Bassa', 'Dekina', 
                'Ibaji', 'Idah', 'Igalamela Odolu', 'Ijumu', 
                'Kabba/Bunu', 'Kogi', 'Lokoja', 'Mopa Muro', 
                'Ofu', 'Ogori/Magongo', 'Okehi', 'Okene', 'Olamaboro', 
                'Omala', 'Yagba East', 'Yagba West'
            ],
            'Kwara' => [
                'Asa', 'Baruten', 'Edu', 'Ekiti', 'Ifelodun', 
                'Ilorin East', 'Ilorin South', 'Ilorin West', 
                'Irepodun', 'Isin', 'Kaiama', 'Moro', 'Offa', 
                'Oke Ero', 'Oyun', 'Pategi'
            ],
            'Lagos' => [
                'Agege', 'Ajeromi-Ifelodun', 'Alimosho', 'Amuwo-Odofin', 
                'Apapa', 'Badagry', 'Epe', 'Eti-Osa', 'Ibeju-Lekki', 
                'Ifako-Ijaiye', 'Ikeja', 'Ikorodu', 'Kosofe', 
                'Lagos Island', 'Lagos Mainland', 'Mushin', 'Ojo', 
                'Oshodi-Isolo', 'Shomolu', 'Surulere'
            ],
            'Nasarawa' => [
                'Akwanga', 'Awe', 'Doma', 'Karu', 'Keana', 
                'Keffi', 'Kokona', 'Lafia', 'Nasarawa', 'Nasarawa Egon', 
                'Obi', 'Toto', 'Wamba'
            ],
            'Niger' => [
                'Agaie', 'Agwara', 'Bida', 'Borgu', 'Bosso', 
                'Chanchaga', 'Edati', 'Gbako', 'Gurara', 'Katcha', 
                'Kontagora', 'Lapai', 'Lavun', 'Magama', 'Mariga', 
                'Mashegu', 'Mokwa', 'Moya', 'Paikoro', 'Rafi', 
                'Rijau', 'Shiroro', 'Suleja', 'Tafa', 'Wushishi'
            ],
            'Ogun' => [
                'Abeokuta North', 'Abeokuta South', 'Ado-Odo/Ota', 
                'Egbado North', 'Egbado South', 'Ewekoro', 'Ifo', 
                'Ijebu East', 'Ijebu North', 'Ijebu North East', 
                'Ijebu Ode', 'Ikenne', 'Imeko Afon', 'Ipokia', 
                'Obafemi Owode', 'Odeda', 'Odogbolu', 'Ogun Waterside', 
                'Remo North', 'Shagamu', 'Yewa North', 'Yewa South'
            ],
            'Ondo' => [
                'Akoko North-East', 'Akoko North-West', 'Akoko South-East', 
                'Akoko South-West', 'Akure North', 'Akure South', 
                'Ese Odo', 'Idanre', 'Ifedore', 'Ilaje', 'Ile Oluji/Okeigbo', 
                'Irele', 'Odigbo', 'Okitipupa', 'Ondo East', 'Ondo West', 
                'Ose', 'Owo'
            ],
            'Osun' => [
                'Aiyedade', 'Aiyedire', 'Atakunmosa East', 'Atakunmosa West', 
                'Boluwaduro', 'Boripe', 'Ede North', 'Ede South', 
                'Egbedore', 'Ejigbo', 'Ife Central', 'Ife East', 
                'Ife North', 'Ife South', 'Ifedayo', 'Ifelodun', 
                'Ila', 'Ilesa East', 'Ilesa West', 'Irepodun', 
                'Irewole', 'Isokan', 'Iwo', 'Obokun', 'Odo Otin', 
                'Ola Oluwa', 'Olorunda', 'Oriade', 'Orolu', 'Osogbo'
            ],
            'Oyo' => [
                'Afijio', 'Akinyele', 'Atiba', 'Atisbo', 'Egbeda', 
                'Ibadan North', 'Ibadan North-East', 'Ibadan North-West', 
                'Ibadan South-East', 'Ibadan South-West', 'Ibarapa Central', 
                'Ibarapa East', 'Ibarapa North', 'Ido', 'Irepo', 
                'Iseyin', 'Itesiwaju', 'Iwajowa', 'Kajola', 'Lagelu', 
                'Ogbomosho North', 'Ogbomosho South', 'Ogo Oluwa', 
                'Olorunsogo', 'Oluyole', 'Ona Ara', 'Orelope', 'Ori Ire', 
                'Oyo East', 'Oyo West', 'Saki East', 'Saki West', 'Surulere'
            ],
            'Plateau' => [
                'Barkin Ladi', 'Bassa', 'Bokkos', 'Jos East', 'Jos North', 
                'Jos South', 'Kanam', 'Kanke', 'Langtang North', 
                'Langtang South', 'Mangu', 'Mikang', 'Pankshin', 
                'Qua\'an Pan', 'Riyom', 'Shendam', 'Wase'
            ],
            'Rivers' => [
                'Abua/Odual', 'Ahoada East', 'Ahoada West', 'Akuku-Toru', 
                'Andoni', 'Asari-Toru', 'Bonny', 'Degema', 'Eleme', 
                'Emohua', 'Etche', 'Gokana', 'Ikwerre', 'Khana', 
                'Obio/Akpor', 'Ogba/Egbema/Ndoni', 'Ogu/Bolo', 
                'Okrika', 'Omuma', 'Opobo/Nkoro', 'Oyigbo', 
                'Port Harcourt', 'Tai'
            ],
            'Sokoto' => [
                'Binji', 'Bodinga', 'Dange Shuni', 'Gada', 'Goronyo', 
                'Gudu', 'Gwadabawa', 'Illela', 'Isa', 'Kebbe', 
                'Kware', 'Rabah', 'Sabon Birni', 'Shagari', 'Silame', 
                'Sokoto North', 'Sokoto South', 'Tambuwal', 'Tangaza', 
                'Tureta', 'Wamako', 'Wurno', 'Yabo'
            ],
            'Taraba' => [
                'Ardo Kola', 'Bali', 'Donga', 'Gashaka', 'Gassol', 
                'Ibi', 'Jalingo', 'Karim Lamido', 'Kumi', 'Lau', 
                'Sardauna', 'Takum', 'Ussa', 'Wukari', 'Yorro', 'Zing'
            ],
            'Yobe' => [
                'Bade', 'Bursari', 'Damaturu', 'Fika', 'Fune', 
                'Geidam', 'Gujba', 'Gulani', 'Jakusko', 'Karasuwa', 
                'Machina', 'Nangere', 'Nguru', 'Potiskum', 'Tarmuwa', 
                'Yunusari', 'Yusufari'
            ],
            'Zamfara' => [
                'Anka', 'Bakura', 'Birnin Magaji/Kiyaw', 'Bukkuyum', 
                'Bungudu', 'Gummi', 'Gusau', 'Kaura Namoda', 'Maradun', 
                'Maru', 'Shinkafi', 'Talata Mafara', 'Chafe', 'Zurmi'
            ]
        ];

        $statesCreated = 0;
        $lgasCreated = 0;

        foreach ($nigerianStates as $stateName => $lgas) {
            // Check if state exists
            $state = State::where('country_id', $nigeria->id)
                ->where('name', $stateName)
                ->first();

            if (!$state) {
                $state = State::create([
                    'country_id' => $nigeria->id,
                    'name' => $stateName,
                    'code' => $this->generateStateCode($stateName),
                ]);
                $statesCreated++;
            }

            // Seed LGAs for this state
            foreach ($lgas as $lgaName) {
                $lgaExists = Lga::where('state_id', $state->id)
                    ->where('name', $lgaName)
                    ->exists();

                if (!$lgaExists) {
                    Lga::create([
                        'state_id' => $state->id,
                        'name' => $lgaName,
                    ]);
                    $lgasCreated++;
                }
            }

            $this->command->info("✓ {$stateName}: " . count($lgas) . " LGAs");
        }

        $this->command->info("✅ Nigerian locations seeded: {$statesCreated} states, {$lgasCreated} LGAs");
    }

    /**
     * Seed USA states (with major cities as LGAs)
     */
    private function seedUSALocations()
    {
        $this->command->info('Seeding USA locations...');

        $usa = Country::where('name', 'United States')
            ->orWhere('iso2', 'US')
            ->orWhere('iso3', 'USA')
            ->first();

        if (!$usa) {
            $this->command->warn('USA not found in countries table.');
            return;
        }

        $usStates = [
            'Alabama' => ['Birmingham', 'Montgomery', 'Mobile', 'Huntsville', 'Tuscaloosa'],
            'Alaska' => ['Anchorage', 'Fairbanks', 'Juneau', 'Sitka', 'Ketchikan'],
            'California' => ['Los Angeles', 'San Francisco', 'San Diego', 'Sacramento', 'San Jose'],
            'Florida' => ['Miami', 'Orlando', 'Tampa', 'Jacksonville', 'Tallahassee'],
            'New York' => ['New York City', 'Buffalo', 'Rochester', 'Yonkers', 'Syracuse'],
            'Texas' => ['Houston', 'Dallas', 'Austin', 'San Antonio', 'Fort Worth'],
            'Illinois' => ['Chicago', 'Springfield', 'Peoria', 'Rockford', 'Naperville'],
            'Pennsylvania' => ['Philadelphia', 'Pittsburgh', 'Allentown', 'Erie', 'Reading'],
        ];

        $this->seedCountryLocations($usa, $usStates, 'USA');
    }

    /**
     * Seed Canada provinces (with major cities as LGAs)
     */
    private function seedCanadaLocations()
    {
        $this->command->info('Seeding Canada locations...');

        $canada = Country::where('name', 'Canada')
            ->orWhere('iso2', 'CA')
            ->orWhere('iso3', 'CAN')
            ->first();

        if (!$canada) {
            $this->command->warn('Canada not found in countries table.');
            return;
        }

        $canadianProvinces = [
            'Ontario' => ['Toronto', 'Ottawa', 'Mississauga', 'Brampton', 'Hamilton'],
            'Quebec' => ['Montreal', 'Quebec City', 'Laval', 'Gatineau', 'Longueuil'],
            'British Columbia' => ['Vancouver', 'Victoria', 'Surrey', 'Burnaby', 'Richmond'],
            'Alberta' => ['Calgary', 'Edmonton', 'Red Deer', 'Lethbridge', 'St. Albert'],
            'Manitoba' => ['Winnipeg', 'Brandon', 'Steinbach', 'Thompson', 'Portage la Prairie'],
        ];

        $this->seedCountryLocations($canada, $canadianProvinces, 'Canada');
    }

    /**
     * Seed UK countries and regions
     */
    private function seedUKLocations()
    {
        $this->command->info('Seeding UK locations...');

        $uk = Country::where('name', 'United Kingdom')
            ->orWhere('iso2', 'GB')
            ->orWhere('iso3', 'GBR')
            ->first();

        if (!$uk) {
            $this->command->warn('UK not found in countries table.');
            return;
        }

        $ukRegions = [
            'England' => ['London', 'Manchester', 'Birmingham', 'Liverpool', 'Leeds'],
            'Scotland' => ['Edinburgh', 'Glasgow', 'Aberdeen', 'Dundee', 'Inverness'],
            'Wales' => ['Cardiff', 'Swansea', 'Newport', 'Bangor', 'St Davids'],
            'Northern Ireland' => ['Belfast', 'Derry', 'Lisburn', 'Newry', 'Bangor'],
        ];

        $this->seedCountryLocations($uk, $ukRegions, 'UK');
    }

    /**
     * Seed Ghana regions
     */
    private function seedGhanaLocations()
    {
        $this->command->info('Seeding Ghana locations...');

        $ghana = Country::where('name', 'Ghana')
            ->orWhere('iso2', 'GH')
            ->orWhere('iso3', 'GHA')
            ->first();

        if (!$ghana) {
            $this->command->warn('Ghana not found in countries table.');
            return;
        }

        $ghanaRegions = [
            'Greater Accra' => ['Accra', 'Tema', 'Ashaiman', 'Nungua', 'Labadi'],
            'Ashanti' => ['Kumasi', 'Obuasi', 'Ejisu', 'Mampong', 'Konongo'],
            'Western' => ['Takoradi', 'Sekondi', 'Tarkwa', 'Axim', 'Half Assini'],
            'Eastern' => ['Koforidua', 'Nsawam', 'Suhum', 'Akosombo', 'Aburi'],
            'Central' => ['Cape Coast', 'Kasoa', 'Winneba', 'Elmina', 'Mankessim'],
        ];

        $this->seedCountryLocations($ghana, $ghanaRegions, 'Ghana');
    }

    /**
     * Seed South Africa provinces
     */
    private function seedSouthAfricaLocations()
    {
        $this->command->info('Seeding South Africa locations...');

        $southAfrica = Country::where('name', 'South Africa')
            ->orWhere('iso2', 'ZA')
            ->orWhere('iso3', 'ZAF')
            ->first();

        if (!$southAfrica) {
            $this->command->warn('South Africa not found in countries table.');
            return;
        }

        $saProvinces = [
            'Gauteng' => ['Johannesburg', 'Pretoria', 'Soweto', 'Vereeniging', 'Krugersdorp'],
            'Western Cape' => ['Cape Town', 'Stellenbosch', 'Paarl', 'Worcester', 'George'],
            'KwaZulu-Natal' => ['Durban', 'Pietermaritzburg', 'Newcastle', 'Richards Bay', 'Ladysmith'],
            'Eastern Cape' => ['Port Elizabeth', 'East London', 'Grahamstown', 'Umtata', 'Butterworth'],
            'Free State' => ['Bloemfontein', 'Welkom', 'Bethlehem', 'Kroonstad', 'Sasolburg'],
        ];

        $this->seedCountryLocations($southAfrica, $saProvinces, 'South Africa');
    }

    /**
     * Generic function to seed country locations
     */
    private function seedCountryLocations($country, $states, $countryName)
    {
        $statesCreated = 0;
        $lgasCreated = 0;

        foreach ($states as $stateName => $lgas) {
            // Check if state exists
            $state = State::where('country_id', $country->id)
                ->where('name', $stateName)
                ->first();

            if (!$state) {
                $state = State::create([
                    'country_id' => $country->id,
                    'name' => $stateName,
                    'code' => $this->generateStateCode($stateName),
                ]);
                $statesCreated++;
            }

            // Seed LGAs for this state
            foreach ($lgas as $lgaName) {
                $lgaExists = Lga::where('state_id', $state->id)
                    ->where('name', $lgaName)
                    ->exists();

                if (!$lgaExists) {
                    Lga::create([
                        'state_id' => $state->id,
                        'name' => $lgaName,
                    ]);
                    $lgasCreated++;
                }
            }
        }

        $this->command->info("✅ {$countryName} locations seeded: {$statesCreated} states/provinces, {$lgasCreated} LGAs/cities");
    }

    /**
     * Generate state code from state name
     */
    private function generateStateCode($stateName): string
    {
        // Remove non-alphabetic characters and take first 3 letters
        $code = preg_replace('/[^a-zA-Z]/', '', $stateName);
        return strtoupper(substr($code, 0, 3));
    }

    /**
     * EXAMPLE: How to add India (uncomment to use)
     */
    private function seedIndiaLocations()
    {
        $this->command->info('Seeding India locations...');

        $india = Country::where('name', 'India')
            ->orWhere('iso2', 'IN')
            ->orWhere('iso3', 'IND')
            ->first();

        if (!$india) {
            $this->command->warn('India not found in countries table.');
            return;
        }

        $indianStates = [
            'Maharashtra' => ['Mumbai', 'Pune', 'Nagpur', 'Nashik', 'Aurangabad'],
            'Delhi' => ['New Delhi', 'Central Delhi', 'North Delhi', 'South Delhi', 'East Delhi'],
            'Karnataka' => ['Bangalore', 'Mysore', 'Hubli', 'Belgaum', 'Mangalore'],
            'Tamil Nadu' => ['Chennai', 'Coimbatore', 'Madurai', 'Tiruchirappalli', 'Salem'],
            'Uttar Pradesh' => ['Lucknow', 'Kanpur', 'Varanasi', 'Agra', 'Allahabad'],
        ];

        $this->seedCountryLocations($india, $indianStates, 'India');
    }

    /**
     * EXAMPLE: How to add Brazil (uncomment to use)
     */
    private function seedBrazilLocations()
    {
        $this->command->info('Seeding Brazil locations...');

        $brazil = Country::where('name', 'Brazil')
            ->orWhere('iso2', 'BR')
            ->orWhere('iso3', 'BRA')
            ->first();

        if (!$brazil) {
            $this->command->warn('Brazil not found in countries table.');
            return;
        }

        $brazilianStates = [
            'São Paulo' => ['São Paulo', 'Campinas', 'Santos', 'Ribeirão Preto', 'Sorocaba'],
            'Rio de Janeiro' => ['Rio de Janeiro', 'Niterói', 'Petrópolis', 'Campos', 'Volta Redonda'],
            'Minas Gerais' => ['Belo Horizonte', 'Uberlândia', 'Contagem', 'Juiz de Fora', 'Betim'],
            'Bahia' => ['Salvador', 'Feira de Santana', 'Vitória da Conquista', 'Camaçari', 'Itabuna'],
            'Rio Grande do Sul' => ['Porto Alegre', 'Caxias do Sul', 'Pelotas', 'Canoas', 'Santa Maria'],
        ];

        $this->seedCountryLocations($brazil, $brazilianStates, 'Brazil');
    }
}