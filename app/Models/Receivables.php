<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Receivables extends Model
{
    use HasFactory;

    const NAME  = 'Receivables';
    protected $table = 'receivables';
    public $timestamps = false;

    protected $fillable = [
        'q_rec_clientID',
        'q_rec_debtorName',
        'q_rec_loanPurpose',
        'q_rec_estimatedValue',
        'q_rec_percentCollectability',
        'q_rec_exclusiveConjugal',
        'q_rec_shareSelf',
        'q_rec_shareSpouse',
        'q_rec_withCli',
        'q_rec_renewalMonth',
        'q_rec_dateUpdated',
        'q_rec_dateCreated',
    ];
}
