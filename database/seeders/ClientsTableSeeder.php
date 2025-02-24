<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Client;
use Faker\Factory as Faker;

class ClientsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        date_default_timezone_set('Asia/Manila');
        $genders = ['Male', 'Female', 'Other'];
        $civilStatuses = ['Single', 'Married', 'Divorced', 'Widowed'];
        $healthConditions = ['Excellent', 'Good', 'Fair', 'Poor', 'Chronic Illness'];
        $faker = Faker::create();
        for ($i = 0; $i < 10; $i++) {
            Client::create([
                'q_clnt_agnt_id' => $faker->numberBetween(1, 10),
                'q_clnt_fneeds_id' => $faker->numberBetween(1, 10),
                'q_clnt_f_name' => $faker->firstName,
                'q_clnt_m_name' => $faker->lastName,
                'q_clnt_l_name' => $faker->lastName,
                'q_clnt_birthDate' => $faker->date,
                'q_clnt_gendr' => $faker->randomElement($genders),
                'q_clnt_contNo' => $faker->phoneNumber,
                'q_clnt_emailAddrx' => $faker->email,
                'q_clnt_civilStatx' => $faker->randomElement($civilStatuses),
                'q_clnt_haveChildren' => 0,
                'q_clnt_shareToSpouse' => $faker->boolean,
                'q_clnt_weddDate' => $faker->date,
                'q_clnt_healthCondi' => $faker->boolean,
                'q_clnt_healthCondiDetail' => $faker->randomElement($healthConditions),
                'q_clnt_takeRiskAssessM' => $faker->boolean,
                'q_clnt_risk_cap' => $faker->lastName,
                'q_clnt_risk_attix' => $faker->lastName,
                'q_clnt_successfulDateSync' => date('Y-m-d h:m:s'),
                'q_clnt_lastLoggedIn' => date('Y-m-d h:m:s'),
                'q_clnt_isActive' => $faker->boolean,
                'q_clnt_addxDate' => $faker->date,
                'q_clnt_updxDate' => $faker->date,
            ]);
        }
    }
}
