<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DebtsAndFinalList;

class DebtsAndFinalListSeed extends Seeder
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
                'q_debtFin_debFinList_desc' => "LOANS NOT COVERED BY MORTGAGE REDEMPTION INSURANCE OR CREDIT LIFE INSURANCE",
                'q_debtFin_isOtherCreated' => 0,
                'q_debtFin_order' => 1,
                'q_debtFin_dateCreated' => date('Y-m-d'),
            ],
            [
                'q_debtFin_debFinList_desc' => "DESIRE STAND BY FUNDS TO PAY OFF FUNERAL AND MEDICAL EXPENSES",
                'q_debtFin_isOtherCreated' => 0,
                'q_debtFin_order' => 2,
                'q_debtFin_dateCreated' => date('Y-m-d'),
            ],
            [
                'q_debtFin_debFinList_desc' => "UNFUNDED EDUCATIONAL NEEDS",
                'q_debtFin_isOtherCreated' => 0,
                'q_debtFin_order' => 3,
                'q_debtFin_dateCreated' => date('Y-m-d'),
            ],
            [
                'q_debtFin_debFinList_desc' => "GIFT TO PARENTS",
                'q_debtFin_isOtherCreated' => 0,
                'q_debtFin_order' => 4,
                'q_debtFin_dateCreated' => date('Y-m-d'),
            ],
        ];
        foreach ($data as $item) {
            DebtsAndFinalList::create($item);
        }
    }
}
