<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DreamsAspirations extends Model
{
    use HasFactory;
    
    const NAME  = 'DreamsAspirations';
    protected $table = 'dreams_aspirations';
    public $timestamps = false;

    protected $fillable = [
        'q_dreAsp_client_id',
        'q_dreAsp_goals',
        'q_dreAsp_otherGoals',
        'q_dreAsp_typeTargetAmount',
        'q_dreAsp_timeline',
        'q_dreAsp_dateUpdated',
        'q_dreAsp_dateCreated'
    ];
}
