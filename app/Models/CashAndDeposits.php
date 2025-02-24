<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashAndDeposits extends Model
{
    use HasFactory;

    const NAME  = 'CashAndDeposits';
    protected $table = 'cash_and_deposits';
    public $timestamps = false;

    protected $fillable = [
        'q_cad_clientID',
        'q_cad_bank',
        'q_cad_accountDescription',
        'q_cad_typeOfAccount',
        'q_cad_estimatedValue',
        'q_cad_purpose',
        'q_cad_withGuaranteedPayout',
        'q_cad_exclusiveConjugal',
        'q_cad_shareSelf',
        'q_cad_shareSpouse',

        'q_cad_accNo',
        'q_cad_insuProd',
        'q_cad_projRate',
        'q_cad_projValEducAge',
        'q_cad_regPayoutAmt',
        'q_cad_ageStartPayout',
        'q_cad_startYearForPayout',
        'q_cad_freqOfPayout',
        'q_cad_ageChildForLastPayout',
        'q_cad_endYearForPayout',

        'q_cad_dateUpdated',
        'q_cad_dateCreated',
    ];
}
