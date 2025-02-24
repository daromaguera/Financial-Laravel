<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AdminSettings extends Model
{
    use HasFactory;
    
    const NAME  = 'AdminSettings';
    protected $table = 'admin_settings';
    public $timestamps = false;

    protected $fillable = [
        'q_admSett_lastUpdatedByID',
        'q_admSett_famProInflaRate',
        'q_admSett_retInflationRate',
        'q_admSett_retEstInterestRate',
        'q_admSett_childEducInflaRate',
        'q_admSett_estateConvCurrTaxRate',
        'q_admSett_estateConvOtherExpenses',
        'q_admSett_ageChildGoCollege',
        'q_admSett_dateUpdated',
        'q_admSett_dateCreated'
    ];
}
