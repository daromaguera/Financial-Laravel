<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ErrorLog extends Model
{
    use HasFactory;

    const NAME  = 'ErrorLog';
    protected $table = 'error_logs';
    public $timestamps = false;

    protected $fillable = [
        'q_errLog_description',
        'q_errLog_systemLog',
        'q_errLog_dateCreated',
    ];
}
