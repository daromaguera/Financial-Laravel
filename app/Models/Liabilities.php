<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Liabilities extends Model
{
    use HasFactory;

    const NAME  = 'Liabilities';
    protected $table = 'liabilities';
    public $timestamps = false;

    protected $fillable = [
        'q_lia_clientID',
        'q_lia_creditorName',
        'q_lia_type',
        'q_lia_totalUnpaidAmt',
        'q_lia_annualInterestRate',
        'q_lia_amtOfMRI',
        'q_lia_uncovered',
        'q_lia_exclusiveConjugal',
        'q_lia_shareSelf',
        'q_lia_shareSpouse',
        'q_lia_dateUpdated',
        'q_lia_dateCreated',
    ];
}
