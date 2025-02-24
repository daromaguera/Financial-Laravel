<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Admin extends Model
{
    use HasFactory;
    
    const NAME  = 'Admin';
    protected $table = 'admin';
    public $timestamps = false;

    protected $fillable = [
        'q_ADm_id',
        'q_ADm_token',
        'q_ADm_type',
        'q_ADm_fN',
        'q_ADm_lN',
        'q_ADm_mN',
        'q_ADm_addrx',
        'q_ADm_profileImage',
        'q_ADm_successfulDateSync',
        'q_ADm_lastLoggedIn',
        'q_ADm_isActive',
    ];
}
