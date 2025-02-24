<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DebtsAndFinalList extends Model
{
    use HasFactory;
    
    const NAME  = 'DebtsAndFinalList';
    protected $table = 'debts_and_final_lists';
    public $timestamps = false;

    protected $fillable = [
        'q_debtFin_debFinList_desc',
        'q_debtFin_isOtherCreated',
        'q_debtFin_order',
        'q_debtFin_dateCreated',
    ];
}
