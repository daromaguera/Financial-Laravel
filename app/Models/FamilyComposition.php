<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FamilyComposition extends Model
{
    use HasFactory;

    const NAME  = 'FamilyComposition';
    protected $table = 'family_compositions';
    public $timestamps = false;

    protected $fillable = [
        'q_famComp_clientID',
        'q_famComp_firstName',
        'q_famComp_lastName',
        'q_famComp_middleInitial',
        'q_famComp_compType',
        'q_famComp_withWithoutChildren',
        'q_famComp_dateMarried',
        'q_famComp_birthDay',
        'q_famComp_healthCondition',
        'q_famComp_status',
        'q_famComp_revocableLiving',
        'q_famComp_revocableLast',
        'q_famComp_dateUpdated',
        'q_famComp_dateCreated'
    ];
}
