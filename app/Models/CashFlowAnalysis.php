<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashFlowAnalysis extends Model
{
    use HasFactory;

    const NAME  = 'CashFlowAnalysis';
    protected $table = 'cash_flow_analysis';
    public $timestamps = false;

    protected $fillable = [
        'q_cfa_clnt_id',
        'q_cfa_targetCashInF_client',
        'q_cfa_targetCashInF_spouse',
        'q_cfa_targetCashOutF_client',
        'q_cfa_targetCashOutF_spouse',
        'q_cfa_clientShareRFN',
        'q_cfa_spouseShareRFN',
        'q_cfa_expectedSavings',
        'q_cfa_goesWell',
        'q_cfa_reduceCFAttempt',
        'q_cfa_dateUpdated',
        'q_cfa_dateCreated'
    ];
}
