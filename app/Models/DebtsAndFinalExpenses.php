<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DebtsAndFinalExpenses extends Model
{
    use HasFactory;

    const NAME  = 'DebtsAndFinalExpenses';
    protected $table = 'debts_and_final_expenses';
    public $timestamps = false;

    protected $fillable = [
        'q_debtFinExp_client_id',
        'q_debtFinExp_debFinList_id',
        'q_debtFinExp_amount_on_client',
        'q_debtFinExp_amount_on_spouse',
        'q_debtFinExp_dateUpdated',
        'q_debtFinExp_dateCreated',
    ];
}
