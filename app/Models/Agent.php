<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agent extends Model
{
    use HasFactory;

    const NAME  = 'agents';
    protected $table = 'agents';
    public $timestamps = false;

    protected $fillable = [
        'q_agnt_id',
        'q_agnt_token',
        'q_agnt_f_name',
        'q_agnt_m_name',
        'q_agnt_l_name',
        'q_agnt_addrx',
        'q_agnt_profileImage',
        'q_agnt_successfulDateSync',
        'q_agnt_lastLoggedIn',
        'q_agnt_isActive',
        'q_agnt_linkLastVisited',
        'q_agnt_uType'
    ];
}
