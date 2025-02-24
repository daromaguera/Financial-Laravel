<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CashFlowData extends Model
{
    use HasFactory;

    const NAME  = 'CashFlowData';
    protected $table = 'cash_flow_data';
    public $timestamps = false;

    protected $fillable = [
        'q_cfd_clnt_id',
        'q_cfd_cfl_id',
        'q_cfd_isNeedsForClient',
        'q_cfd_cfda_clientAmt',
        'q_cfd_isNeedsForSpouse',
        'q_cfd_cfda_spouseAmt',
        'q_cfd_cfda_clientAmtExpense',
        'q_cfd_cfda_spouseAmtExpense',
        'q_cfd_cfdb_clientAmt',
        'q_cfd_cfdb_spouseAmt',
        'q_cfd_targetRetireAmtInPercent',
        'q_cfd_dateUpdated',
        'q_cfd_dateCreated'
    ];
}
