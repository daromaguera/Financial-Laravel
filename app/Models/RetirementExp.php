<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RetirementExp extends Model
{
    use HasFactory;
    
    const NAME  = 'RetirementExp';
    protected $table = 'retirement_exps';
    public $timestamps = false;

    protected $fillable = [
        'q_retExp_clientID',
        'q_retExp_retExpList_id',
        'q_retExp_presentValAmtCL',
        'q_retExp_presentValAmtSP',
        'q_retExp_dateCreated',
    ];
}
