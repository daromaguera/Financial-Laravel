<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StocksInCompanies extends Model
{
    use HasFactory;

    const NAME  = 'StocksInCompanies';
    protected $table = 'stocks_in_companies';
    public $timestamps = false;

    protected $fillable = [
        'q_stoComp_clientID',
        'q_stoComp_companyAlias',
        'q_stoComp_noOfShares',
        'q_stoComp_currentBookValueShare',
        'q_stoComp_estimatedValue',
        'q_stoComp_purpose',
        'q_stoComp_exclusiveConjugal',
        'q_stoComp_shareSelf',
        'q_stoComp_shareSpouse',
        'q_stoComp_isListed',

        'q_stoComp_accNo',
        'q_stoComp_insuProd',
        'q_stoComp_projRate',
        'q_stoComp_projValEducAge',
        'q_stoComp_regPayoutAmt',
        'q_stoComp_ageStartPayout',
        'q_stoComp_startYearForPayout',
        'q_stoComp_freqOfPayout',
        'q_stoComp_ageChildForLastPayout',
        'q_stoComp_endYearForPayout',
        
        'q_stoComp_dateUpdated',
        'q_stoComp_dateCreated',
    ];
}
