<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemLogs extends Model
{
    use HasFactory;
    
    const NAME  = 'SystemLogs';
    protected $table = 'system_logs';
    public $timestamps = false;

    protected $fillable = [
        'q_SysLogs_logDescription',
        'q_SysLogs_dateCreated',
    ];
}
