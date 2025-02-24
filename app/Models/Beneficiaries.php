<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Beneficiaries extends Model
{
    use HasFactory;

    const NAME  = 'Beneficiaries';
    protected $table = 'beneficiaries';
    public $timestamps = false;

    protected $fillable = [
        'q_benex_lifeHeath_id',
        'q_benex_fullName',
        'q_benex_percentShare',
        'q_benex_designation',
        'q_benex_priority',
        'q_benex_dateUpdated',
        'q_benex_dateCreated',
    ];
}
