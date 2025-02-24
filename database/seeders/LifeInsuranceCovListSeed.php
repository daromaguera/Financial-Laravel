<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\LifeInsuranceCoverageList;

class LifeInsuranceCovListSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        date_default_timezone_set('Asia/Manila');
        $data = [
            [
                'q_lifeInsCovList_debFinListDesc' => "SSS/GSIS BENEFIT",
                'q_lifeInsCovList_isOtherCreated' => 0,
                'q_lifeInsCovList_order' => 1,
                'q_lifeInsCovList_dateCreated' => date('Y-m-d'),
            ],
            [
                'q_lifeInsCovList_debFinListDesc' => "COMPANY BENEFIT",
                'q_lifeInsCovList_isOtherCreated' => 0,
                'q_lifeInsCovList_order' => 2,
                'q_lifeInsCovList_dateCreated' => date('Y-m-d'),
            ],
        ];
        foreach ($data as $item) {
            LifeInsuranceCoverageList::create($item);
        }
    }
}
