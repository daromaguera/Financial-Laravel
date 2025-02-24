<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TargetLimits extends Model
{
    use HasFactory;
    
    const NAME  = 'TargetLimits';
    protected $table = 'target_limits';
    public $timestamps = false;

    protected $fillable = [
        'q_targLim_clientID',
        'q_targLim_famCompID',
        'q_targLim_type',
        'q_targLim_MBL_inPatient',
        'q_targLim_ABL_inPatient',
        'q_targLim_LBL_inPatient',
        'q_targLim_MBL_outPatient',
        'q_targLim_ABL_outPatient',
        'q_targLim_LBL_outPatient',
        'q_targLim_MBL_critIllness',
        'q_targLim_ABL_critIllness',
        'q_targLim_LBL_critIllness',
        'q_targLim_labLimit',
        'q_targLim_hospIncome',
        'q_targLim_dateUpdate',
        'q_targLim_dateCreated'
    ];
}
