<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LifeInsuranceCoverageList extends Model
{
    use HasFactory;

    const NAME  = 'LifeInsuranceCoverageList';
    protected $table = 'life_insurance_coverage_lists';
    public $timestamps = false;

    protected $fillable = [
        'q_lifeInsCovList_debFinListDesc',
        'q_lifeInsCovList_isOtherCreated',
        'q_lifeInsCovList_order',
        'q_lifeInsCovList_dateCreated',
    ];
}
