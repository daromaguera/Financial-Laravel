<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\CashFlowList;

class CashFlowListSeeder extends Seeder
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
                'q_cfl_descripx' => "Income from Employment (Salary)",
                'q_cfl_type' => 0,
                'q_cfl_isOther' => 0,
                'q_cfl_order' => 1,
                'q_cfl_dateCreated' => date('Y-m-d'),
            ],
            [
                'q_cfl_descripx' => "Income from Business",
                'q_cfl_type' => 0,
                'q_cfl_isOther' => 0,
                'q_cfl_order' => 2,
                'q_cfl_dateCreated' => date('Y-m-d'),
            ],
            [
                'q_cfl_descripx' => "Income from Part time job",
                'q_cfl_type' => 0,
                'q_cfl_isOther' => 0,
                'q_cfl_order' => 3,
                'q_cfl_dateCreated' => date('Y-m-d'),
            ],
            [
                'q_cfl_descripx' => "Passive Income (Fixed/Interests)",
                'q_cfl_type' => 0,
                'q_cfl_isOther' => 0,
                'q_cfl_order' => 4,
                'q_cfl_dateCreated' => date('Y-m-d'),
            ],
            [
                'q_cfl_descripx' => "Passive Income (Variable/Estimated)",
                'q_cfl_type' => 0,
                'q_cfl_isOther' => 0,
                'q_cfl_order' => 5,
                'q_cfl_dateCreated' => date('Y-m-d'),
            ],
            [
                'q_cfl_descripx' => "Food (groceries, wet market, dining out)",
                'q_cfl_type' => 1,
                'q_cfl_isOther' => 0,
                'q_cfl_order' => 51, // Order for Outflow starts at 51
                'q_cfl_dateCreated' => date('Y-m-d'),
            ],
            [
                'q_cfl_descripx' => "Clothing, shoes, grooming, shopping",
                'q_cfl_type' => 1,
                'q_cfl_isOther' => 0,
                'q_cfl_order' => 52,
                'q_cfl_dateCreated' => date('Y-m-d'),
            ],
            [
                'q_cfl_descripx' => "Communication (Phone, Internet)",
                'q_cfl_type' => 1,
                'q_cfl_isOther' => 0,
                'q_cfl_order' => 53,
                'q_cfl_dateCreated' => date('Y-m-d'),
            ],
            [
                'q_cfl_descripx' => "Domestic Help, Driver, Gardener, Guards",
                'q_cfl_type' => 1,
                'q_cfl_isOther' => 0,
                'q_cfl_order' => 54,
                'q_cfl_dateCreated' => date('Y-m-d'),
            ],
            [
                'q_cfl_descripx' => "Gasoline, Transportation, Car Maintenance",
                'q_cfl_type' => 1,
                'q_cfl_isOther' => 0,
                'q_cfl_order' => 55,
                'q_cfl_dateCreated' => date('Y-m-d'),
            ],
            [
                'q_cfl_descripx' => "Electricity, Water, Gas",
                'q_cfl_type' => 1,
                'q_cfl_isOther' => 0,
                'q_cfl_order' => 56,
                'q_cfl_dateCreated' => date('Y-m-d'),
            ],
            [
                'q_cfl_descripx' => "Medicine, Dental, Derma, Hospitalization",
                'q_cfl_type' => 1,
                'q_cfl_isOther' => 0,
                'q_cfl_order' => 57,
                'q_cfl_dateCreated' => date('Y-m-d'),
            ],
            [
                'q_cfl_descripx' => "Education (Tuition fees, Allowances, School supplies)",
                'q_cfl_type' => 1,
                'q_cfl_isOther' => 0,
                'q_cfl_order' => 58,
                'q_cfl_dateCreated' => date('Y-m-d'),
            ],
            [
                'q_cfl_descripx' => "Insurance (life, health, pension, memorial, car, fire)",
                'q_cfl_type' => 1,
                'q_cfl_isOther' => 0,
                'q_cfl_order' => 59,
                'q_cfl_dateCreated' => date('Y-m-d'),
            ],
            [
                'q_cfl_descripx' => "Rental Fees, Real Estate Taxes, Assoc. Dues",
                'q_cfl_type' => 1,
                'q_cfl_isOther' => 0,
                'q_cfl_order' => 60,
                'q_cfl_dateCreated' => date('Y-m-d'),
            ],
            [
                'q_cfl_descripx' => "Recreation, Hobbies",
                'q_cfl_type' => 1,
                'q_cfl_isOther' => 0,
                'q_cfl_order' => 61,
                'q_cfl_dateCreated' => date('Y-m-d'),
            ],
            [
                'q_cfl_descripx' => "Credit Card Servicing, Repayment of Debt",
                'q_cfl_type' => 1,
                'q_cfl_isOther' => 0,
                'q_cfl_order' => 62,
                'q_cfl_dateCreated' => date('Y-m-d'),
            ],
            [
                'q_cfl_descripx' => "Loan Amortization (Car, House)",
                'q_cfl_type' => 1,
                'q_cfl_isOther' => 0,
                'q_cfl_order' => 63,
                'q_cfl_dateCreated' => date('Y-m-d'),
            ],
            [
                'q_cfl_descripx' => "Memberships (Gym, Clubs)",
                'q_cfl_type' => 1,
                'q_cfl_isOther' => 0,
                'q_cfl_order' => 64,
                'q_cfl_dateCreated' => date('Y-m-d'),
            ],
            [
                'q_cfl_descripx' => "Other support given",
                'q_cfl_type' => 1,
                'q_cfl_isOther' => 0,
                'q_cfl_order' => 65,
                'q_cfl_dateCreated' => date('Y-m-d'),
            ],
            [
                'q_cfl_descripx' => "Entertainment (Video & Audio Subscriptions)",
                'q_cfl_type' => 1,
                'q_cfl_isOther' => 0,
                'q_cfl_order' => 66,
                'q_cfl_dateCreated' => date('Y-m-d'),
            ],
            [
                'q_cfl_descripx' => "Travels",
                'q_cfl_type' => 1,
                'q_cfl_isOther' => 0,
                'q_cfl_order' => 67,
                'q_cfl_dateCreated' => date('Y-m-d'),
            ],
            [
                'q_cfl_descripx' => "Tithes",
                'q_cfl_type' => 1,
                'q_cfl_isOther' => 0,
                'q_cfl_order' => 68,
                'q_cfl_dateCreated' => date('Y-m-d'),
            ],
        ];
        foreach ($data as $item) {
            CashFlowList::create($item);
        }
    }
}
