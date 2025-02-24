<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExistingLifeInsuranceCoverage extends Model
{
    use HasFactory;
    
    const NAME  = 'ExistingLifeInsuranceCoverage';
    protected $table = 'existing_life_insurance_coverages';
    public $timestamps = false;

    protected $fillable = [
        'q_exLifeInsCov_clientID',
        'q_exLifeInsCov_listID',
        'q_exLifeInsCov_amtClient',
        'q_exLifeInsCov_amtSpouse',
        'q_exLifeInsCov_dateCreated'
    ];
}
