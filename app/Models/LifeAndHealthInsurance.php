<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LifeAndHealthInsurance extends Model
{
    use HasFactory;

    const NAME  = 'LifeAndHealthInsurance';
    protected $table = 'life_and_health_insurances';
    public $timestamps = false;

    protected $fillable = [
        'q_lifeHealth_clientID',
        'q_lifeHealth_fromAetosAdviser',
        'q_lifeHealth_insuranceCompany',
        'q_lifeHealth_policyOwner',
        'q_lifeHealth_policyNumber',
        'q_lifeHealth_typeOfPolicy',
        'q_lifeHealth_monthYearIssued',
        'q_lifeHealth_insured',
        'q_lifeHealth_purpose',
        'q_lifeHealth_withGuaranteedPayout',
        'q_lifeHealth_faceAmountFamilyProtection',
        'q_lifeHealth_faceAmountEstateTax',
        'q_lifeHealth_faceAmountEstateDistribution',
        'q_lifeHealth_faceAmount',
        'q_lifeHealth_currentFundValueEstimated',

        'q_lifeHealth_accNo',
        'q_lifeHealth_insuProd',
        'q_lifeHealth_projRate',
        'q_lifeHealth_projValEducAge',
        'q_lifeHealth_regPayoutAmt',
        'q_lifeHealth_ageStartPayout',
        'q_lifeHealth_startYearForPayout',
        'q_lifeHealth_freqOfPayout',
        'q_lifeHealth_ageChildForLastPayout',
        'q_lifeHealth_endYearForPayout',

        'q_lifeHealth_dateEffective',
        'q_lifeHealth_dateUpdated',
        'q_lifeHealth_dateCreated',
    ];
}
