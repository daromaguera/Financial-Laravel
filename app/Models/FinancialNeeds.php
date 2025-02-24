<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FinancialNeeds extends Model
{
    use HasFactory;

    const NAME  = 'FinancialNeeds';
    protected $table = 'financial_needs';
    public $timestamps = false;

    protected $fillable = [
        'q_fneeds_name',
        'q_fneeds_descripx',
        'q_fneeds_linkPath',
        'q_fneeds_dateCreated'
    ];
}
