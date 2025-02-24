<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RetirementExpList extends Model
{
    use HasFactory;
    
    const NAME  = 'RetirementExpList';
    protected $table = 'retirement_exp_lists';
    public $timestamps = false;

    protected $fillable = [
        'q_retExpList_description',
        'q_retExpList_isOther',
        'q_retExpList_dateCreated',
    ];
}
