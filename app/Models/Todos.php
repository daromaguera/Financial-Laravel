<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Todos extends Model
{
    use HasFactory;

    const NAME  = 'Todos';
    protected $table = 'todos';
    public $timestamps = false;

    protected $fillable = [
        'q_tdo_clientID',
        'q_tdo_agentID',
        'q_tdo_isForClientAgent',
        'q_tdo_descripx',
        'q_tdo_dateTodo',
        'q_tdo_fromTable',
        'q_tdo_isSeen',
        'q_tdo_dateCreated',
        'q_tdo_dateMarkedAsResolved',
        'q_tdo_remarksOnResolved'
    ];
}
