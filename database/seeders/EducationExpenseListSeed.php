<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\EducPlannExpList;

class EducationExpenseListSeed extends Seeder
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
                'q_educPExpList_description' => "ESTIMATED ANNUAL TUITION FEES",
                'q_educPExpList_isOther' => 0,
                'q_educPExpList_order' => 1,
                'q_educPExpList_dateCreated' => date('Y-m-d'),
            ],
        ];
        foreach ($data as $item) {
            EducPlannExpList::create($item);
        }
    }
}
