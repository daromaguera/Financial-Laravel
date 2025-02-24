<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Heir extends Model
{
    use HasFactory;

    const NAME  = 'Heir';
    protected $table = 'heirs';
    public $timestamps = false;

    protected $fillable = [
        'q_heir_famComp_id',
        'q_heir_tableID',
        'q_heir_fromTable',
        'q_heir_indicatedPercentage',   
    ];
}
