<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Agent;
use Faker\Factory as Faker;

class AgentsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        date_default_timezone_set('Asia/Manila');
        $faker = Faker::create();
        for ($i = 0; $i < 10; $i++) {
            if($i == 1){
                Agent::create([
                    'q_agnt_token' => "69dj1029ufmakwjd2190udj01i2ndokanskgawjkid019250912jfgiygj0sada0xa0m13k2mgawkh2i1e01dmazggjwaawd",
                    'q_agnt_f_name' => "DEXTER",
                    'q_agnt_m_name' => "A.",
                    'q_agnt_l_name' => "ROMAGUERA",
                    'q_agnt_addrx' => $faker->address,
                    'q_agnt_successfulDateSync' => date('Y-m-d h:m:s'),
                    'q_agnt_lastLoggedIn' => date('Y-m-d h:m:s'),
                    'q_agnt_isActive' => $faker->boolean,
                ]);
            }else{
                Agent::create([
                    'q_agnt_f_name' => $faker->firstName,
                    'q_agnt_m_name' => $faker->lastName,
                    'q_agnt_l_name' => $faker->lastName,
                    'q_agnt_addrx' => $faker->address,
                    'q_agnt_successfulDateSync' => date('Y-m-d h:m:s'),
                    'q_agnt_lastLoggedIn' => date('Y-m-d h:m:s'),
                    'q_agnt_isActive' => $faker->boolean,
                ]);
            }
        }
    }
}
