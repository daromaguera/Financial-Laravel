<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SelectedFinancialPriorities extends Model
{
    use HasFactory;

    const NAME  = 'SelectedFinancialPriorities';
    protected $table = 'selected_financial_priorities';
    public $timestamps = false;

    protected $fillable = [
        'q_sfp_clnt_id',
        'q_sfp_fp_id',
        'q_sfp_rank',
        'q_sfp_reason',
        'q_sfp_dateCreated',
    ];
}
