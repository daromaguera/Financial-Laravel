<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducPlannExpList extends Model
{
    use HasFactory;
        
    const NAME  = 'EducPlannExpList';
    protected $table = 'educ_plann_exp_lists';
    public $timestamps = false;

    protected $fillable = [
        'q_educPExpList_description',
        'q_educPExpList_isOther',
        'q_educPExpList_order',
        'q_educPExpList_dateCreated',
    ];
}
