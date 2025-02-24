<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RetirePlannFNA extends Model
{
    use HasFactory;

    const NAME  = 'RetirePlannFNA';
    protected $table = 'retire_plann_f_n_a_s';
    public $timestamps = false;

    protected $fillable = [
        'q_retPFNA_clientID',
        'q_retPFNAa_resRetPlann',
        'q_retPFNA_howRetLooks',
        'q_retPFNA_currAgeCL',
        'q_retPFNA_currAgeSP',
        'q_retPFNA_ageRetCL',
        'q_retPFNA_ageRetSP',
        'q_retPFNA_lifeSpanCL',
        'q_retPFNA_lifeSpanSP',
        'q_retPFNA_avgInfaRate',
        'q_retPFNA_intRetirement',
        'q_retPFNA_sssAnnualCL',
        'q_retPFNA_sssAnnualSP',
        'q_retPFNA_yrsSSSBenefitCL',
        'q_retPFNA_yrsSSSBenefitSP',
        'q_retPFNA_companyBenefitRetCL',
        'q_retPFNA_companyBenefitRetSP',
        'q_retPFNA_yrsCompanyBenefitCL',
        'q_retPFNA_yrsCompanyBenefitSP',
        'q_retPFNA_dateUpdated',
        'q_retPFNA_dateCreated'
    ];
}