<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class InvestmentForEducation extends Model
{
    use HasFactory;
    
    const NAME  = 'InvestmentForEducation';
    protected $table = 'investment_for_education';
    public $timestamps = false;

    protected $fillable = [
        'q_invForEduc_tableID',
        'q_invForEduc_fromTable',
        'q_invForEduc_withWithoutPayout',
        'q_invForEduc_policy_no',
        'q_invForEduc_type',
        'q_invForEduc_company',
        'q_invForEduc_cashSurrValue',
        'q_invForEduc_isInsProduct',
        'q_invForEduc_rateOfReturn',
        'q_invForEduc_valueEducAge',
        'q_invForEduc_ageChildPayout',
        'q_invForEduc_valAfterCollege',
        'q_invForEduc_regPayoutAmt',
        'q_invForEduc_startYearPayout',
        'q_invForEduc_freqPayout',
        'q_invForEduc_ageChildLastPayout',
        'q_invForEduc_endYearPayout',
        'q_invForEduc_dateUpdated',
        'q_invForEduc_dateCreated',
    ];
}
