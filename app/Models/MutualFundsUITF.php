<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MutualFundsUITF extends Model
{
    use HasFactory;

    const NAME  = 'MutualFundsUITF';
    protected $table = 'mutual_funds_u_i_t_f';
    public $timestamps = false;

    protected $fillable = [
        'q_uitf_clientID',
        'q_uitf_company',
        'q_uitf_noOfUnits',
        'q_uitf_currentValuePerUnits',
        'q_uitf_estimatedValue',
        'q_uitf_purpose',
        'q_uitf_withGuaranteedPayout',
        'q_uitf_exclusiveConjugal',
        'q_uitf_shareSelf',
        'q_uitf_shareSpouse',

        'q_uitf_accNo',
        'q_uitf_insuProd',
        'q_uitf_projRate',
        'q_uitf_projValEducAge',
        'q_uitf_regPayoutAmt',
        'q_uitf_ageStartPayout',
        'q_uitf_startYearForPayout',
        'q_uitf_freqOfPayout',
        'q_uitf_ageChildForLastPayout',
        'q_uitf_endYearForPayout',

        'q_uitf_dateUpdated',
        'q_uitf_dateCreated',  
    ];
}
