<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducPlannExp extends Model
{
    use HasFactory;
     
    const NAME  = 'EducPlannExp';
    protected $table = 'educ_plann_exps';
    public $timestamps = false;

    protected $fillable = [
        'q_educPExp_famComp_id',
        'q_educPExp_educPExpList_id',
        'q_educPExp_presentValAmt',
        'q_educPExp_avgInflationRate',
        'q_educPExp_futureNeededValAmt',
        'q_educPExp_dateCreated',
    ];
}
