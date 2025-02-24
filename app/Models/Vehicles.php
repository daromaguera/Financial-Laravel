<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicles extends Model
{
    use HasFactory;

    const NAME  = 'Vehicles';
    protected $table = 'vehicles';
    public $timestamps = false;

    protected $fillable = [
        'q_vehicle_clientID',
        'q_vehicle_plateNo',
        'q_vehicle_type',
        'q_vehicle_estimatedValue',
        'q_vehicle_exclusiveConjugal',
        'q_vehicle_shareSelf',
        'q_vehicle_shareSpouse',
        'q_vehicle_withInsurance',
        'q_vehicle_renewalMonth',
        'q_vehicle_accNo',
        'q_vehicle_insuProd',
        'q_vehicle_projRate',
        'q_vehicle_projValEducAge',
        'q_vehicle_dateUpdated',
        'q_vehicle_dateCreated',
    ];
}
