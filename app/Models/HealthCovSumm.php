<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HealthCovSumm extends Model
{
    use HasFactory;
    
    const NAME  = 'HealthCovSumm';
    protected $table = 'health_cov_summs';
    public $timestamps = false;

    protected $fillable = [
        'q_healthCovSum_clientID',
        'q_healthCovSum_famCompID',
        'q_healthCovSum_type',
        'q_healthCovSum_policyRefNo',
        'q_healthCovSum_origin',
        'q_healthCovSum_amtInPatient',
        'q_healthCovSum_opInPatient',
        'q_healthCovSum_amtOutPatient',
        'q_healthCovSum_opOutPatient',
        'q_healthCovSum_amtCritIllLim',
        'q_healthCovSum_opCritIllLim',
        'q_healthCovSum_amtLabLim',
        'q_healthCovSum_amtHospIncome',
        'q_healthCovSum_maxNoDays',
        'q_healthCovSum_notes',
        'q_healthCovSum_dateUpdated',
        'q_healthCovSum_dateCreated'
    ];
}
