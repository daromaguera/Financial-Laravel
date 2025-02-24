<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashFlowList extends Model
{
    use HasFactory;

    const NAME  = 'CashFlowList';
    protected $table = 'cash_flow_lists';
    public $timestamps = false;

    protected $fillable = [
        'q_cfl_descripx',
        'q_cfl_type',
        'q_cfl_isOther',
        'q_cfl_order',
        'q_cfl_dateCreated'
    ];
}
