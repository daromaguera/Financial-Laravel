<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Recommendations extends Model
{
    use HasFactory;

    const NAME  = 'Recommendations';
    protected $table = 'recommendations';
    public $timestamps = false;

    protected $fillable = [
        'q_recommx_cfa_id',
        'q_recommx_recommxDetails',
        'q_recommx_isInflowOutflow',
        'q_recommx_dateCreated'
    ];
}
