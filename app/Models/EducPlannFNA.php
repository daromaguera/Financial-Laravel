<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EducPlannFNA extends Model
{
    use HasFactory;
    
    const NAME  = 'EducPlannFNA';
    protected $table = 'educ_plann_f_n_a_s';
    public $timestamps = false;

    protected $fillable = [
        'q_educPFNA_clientID',
        'q_educPFNA_resEducPlannImp',
        'q_educPFNA_dreamsForChildren',
        'q_educPFNA_dateUpdated',
        'q_educPFNA_dateCreated'
    ];
}
