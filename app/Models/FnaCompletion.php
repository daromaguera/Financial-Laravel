<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FnaCompletion extends Model
{
    use HasFactory;
    
    const NAME  = 'FnaCompletion';
    protected $table = 'fna_completions';
    public $timestamps = false;

    protected $fillable = [
        'q_fnaComp_clientID',
        'q_fnaComp_FNA',
        'q_fnaComp_statusValue',
        'q_fnaComp_dateCreated'
    ];
}
