<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthFundPlannFNA extends Model
{
    use HasFactory;
    
    const NAME  = 'HealthFundPlannFNA';
    protected $table = 'health_fund_plann_f_n_a_s';
    public $timestamps = false;

    protected $fillable = [
        'q_healthFP_clientID',
        'q_healthFP_resHealthFund',
        'q_healthFP_finSitWithIllMember',
        'q_healthFP_finImpact',
        'q_healthFP_dateUpdated',
        'q_healthFP_dateCreated'
    ];
}
