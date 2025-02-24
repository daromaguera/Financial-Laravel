<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FamilyHomeEstate extends Model
{
    use HasFactory;

    const NAME  = 'FamilyHomeEstate';
    protected $table = 'family_homesEstate';
    public $timestamps = false;

    protected $fillable = [
        'q_homeEstate_clientID',
        'q_homeEstate_tctNumber',
        'q_homeEstate_cityMunLocation',
        'q_homeEstate_areaSQM',
        'q_homeEstate_zoneValueEstimate',
        'q_homeEstate_estimatedValue',
        'q_homeEstate_exclusiveConjugal',
        'q_homeEstate_purpose',
        'q_homeEstate_withGuaranteedPayout',
        'q_homeEstate_shareSelf',
        'q_homeEstate_shareSpouse',
        'q_homeEstate_withPropertyInsurance',
        'q_homeEstate_renewalMonth',
        'q_homeEstate_isHome',

        'q_homeEstate_accNo',
        'q_homeEstate_insuProd',
        'q_homeEstate_projRate',
        'q_homeEstate_projValEducAge',
        'q_homeEstate_regPayoutAmt',
        'q_homeEstate_ageStartPayout',
        'q_homeEstate_startYearForPayout',
        'q_homeEstate_freqOfPayout',
        'q_homeEstate_ageChildForLastPayout',
        'q_homeEstate_endYearForPayout',

        'q_homeEstate_dateUpdated',
        'q_homeEstate_dateCreated',
    ];
}
