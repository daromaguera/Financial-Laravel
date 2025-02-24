<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdvActivities extends Model
{
    use HasFactory;
        
    const NAME  = 'AdvActivities';
    protected $table = 'adv_activities';
    public $timestamps = false;

    protected $fillable = [
        'q_advAct_agentID',
        'q_advAct_clientID',
        'q_advAct_actDescription',
        'q_advAct_dateCreated'
    ];
}
