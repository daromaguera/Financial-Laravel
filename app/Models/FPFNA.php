<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FPFNA extends Model
{
    use HasFactory;

    const NAME  = 'FPFNA';
    protected $table = 'f_p_f_n_a_s';
    public $timestamps = false;

    protected $fillable = [
        'q_fpfna_clientID',
        'q_fpfna_finImpDeceased',
        'q_fpfna_avgInflaRate',
        'q_fpfna_annOutflowsCL',
        'q_fpfna_annOutflowsSP',
        'q_fpfna_yearsFamSupp',
        'q_fpfna_annSuppFromCL',
        'q_fpfna_annSuppFromSP',
        'q_fpfna_yearsSuppCL',
        'q_fpfna_yearsSuppSP',
        'q_fpfna_addxLifeInsuCL',
        'q_fpfna_addxLifeInsuSP',
        'q_fpfna_dateUpdated',
        'q_fpfna_dateCreated'
    ];
}
