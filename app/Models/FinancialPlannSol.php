<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialPlannSol extends Model
{
    use HasFactory;
    
    const NAME  = 'FinancialPlannSol';
    protected $table = 'financial_plann_sols';
    public $timestamps = false;

    protected $fillable = [
        'q_finPlSo_clientID',
        'q_finPlSo_forTable',
        'q_finPlSo_monthlyBud1',
        'q_finPlSo_monthlyBud2',
        'q_finPlSo_actNetCashflow1',
        'q_finPlSo_actNetCashflow2',
        'q_finPlSo_modePayment',
        'q_finPlSo_formPayment',
        'q_finPlSo_advisorSuggestion',
        'q_finPlSo_status',
        'q_finPlSo_goalRev',
        'q_finPlSo_meetAdvisorOn',
        'q_finPlSo_dateUpdated',
        'q_finPlSo_dateCreated',
        'q_finPlSo_dateMarkedAsResolved',
        'q_finPlSo_remarksOnResolved'
    ];
}
