<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bonds extends Model
{
    use HasFactory;

    const NAME  = 'Bonds';
    protected $table = 'bonds';
    public $timestamps = false;

    protected $fillable = [
        'q_bond_clientID',
        'q_bond_issuer',
        'q_bond_maturityDate',
        'q_bond_perValue',
        'q_bond_estimatedValue',
        'q_bond_purpose',
        'q_bond_withGuaranteedPayout',
        'q_bond_exclusiveConjugal',
        'q_bond_shareSelf',
        'q_bond_shareSpouse',

        'q_bond_accNo',
        'q_bond_insuProd',
        'q_bond_projRate',
        'q_bond_projValEducAge',
        'q_bond_regPayoutAmt',
        'q_bond_ageStartPayout',
        'q_bond_startYearForPayout',
        'q_bond_freqOfPayout',
        'q_bond_ageChildForLastPayout',
        'q_bond_endYearForPayout',
        
        'q_bond_dateUpdated',
        'q_bond_dateCreated',
    ];
}
