<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PersonalAssets extends Model
{
    use HasFactory;

    const NAME  = 'PersonalAssets';
    protected $table = 'personal_assets';
    public $timestamps = false;

    protected $fillable = [
        'q_perAs_clientID',
        'q_perAs_item',
        'q_perAs_estimatedValue',
        'q_perAs_purpose',
        'q_perAs_withGuaranteedPayout',
        'q_perAs_exclusiveConjugal',
        'q_perAs_shareSelf',
        'q_perAs_shareSpouse',

        'q_perAs_accNo',
        'q_perAs_insuProd',
        'q_perAs_projRate',
        'q_perAs_projValEducAge',
        'q_perAs_regPayoutAmt',
        'q_perAs_ageStartPayout',
        'q_perAs_startYearForPayout',
        'q_perAs_freqOfPayout',
        'q_perAs_ageChildForLastPayout',
        'q_perAs_endYearForPayout',

        'q_perAs_dateUpdated',
        'q_perAs_dateCreated'
    ];
}
