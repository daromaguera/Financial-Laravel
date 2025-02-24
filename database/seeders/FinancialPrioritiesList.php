<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\FinancialPriorities;

class FinancialPrioritiesList extends Seeder
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
                'q_fp_name' => "FAMILY PROTECTION/CLEAN UP FUND - FP FNA",
                'q_fp_descripx' => "No Description...",
                'q_fp_dateCreated' => date('Y-m-d'),
            ],
            [
                'q_fp_name' => "HEALTH FUND- Health FNA",
                'q_fp_descripx' => "No Description...",
                'q_fp_dateCreated' => date('Y-m-d'),
            ],
            [
                'q_fp_name' => "RETIREMENT PLAN- Retirement FNA",
                'q_fp_descripx' => "No Description...",
                'q_fp_dateCreated' => date('Y-m-d'),
            ],
            [
                'q_fp_name' => "CHILDREN'S EDUCATION FUND - Education FNA 1",
                'q_fp_descripx' => "No Description...",
                'q_fp_dateCreated' => date('Y-m-d'),
            ],
            [
                'q_fp_name' => "PARENT'S COVERAGE",
                'q_fp_descripx' => "No Description...",
                'q_fp_dateCreated' => date('Y-m-d'),
            ],
            [
                'q_fp_name' => "CHILDREN'S COVERAGE- Family and Life FNA",
                'q_fp_descripx' => "No Description...",
                'q_fp_dateCreated' => date('Y-m-d'),
            ],
            [
                'q_fp_name' => "ESTATE PLAN- EP Narratives",
                'q_fp_descripx' => "No Description...",
                'q_fp_dateCreated' => date('Y-m-d'),
            ],
            [
                'q_fp_name' => "BUSINESS INSURANCE- Employee - BI Employee",
                'q_fp_descripx' => "No Description...",
                'q_fp_dateCreated' => date('Y-m-d'),
            ],
        ];
        foreach ($data as $item) {
            FinancialPriorities::create($item);
        }
    }
}
