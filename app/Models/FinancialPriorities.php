<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialPriorities extends Model
{
    use HasFactory;

    const NAME  = 'FinancialPriorities';
    protected $table = 'financial_priorities';
    public $timestamps = false;

    protected $fillable = [
        'q_fp_name',
        'q_fp_descripx',
        'q_fp_dateCreated'
    ];
}
