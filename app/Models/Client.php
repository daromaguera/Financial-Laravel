<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    const NAME  = 'clients';
    protected $table = 'clients';
    public $timestamps = false;

    protected $fillable = [
        'q_clnt_id',
        'q_clnt_agnt_id',
        'q_clnt_fneeds_id',
        'q_clnt_spouseID',
        'q_clnt_f_name',
        'q_clnt_m_name',
        'q_clnt_l_name',
        'q_clnt_birthDate',
        'q_clnt_gendr',
        'q_clnt_contNo',
        'q_clnt_emailAddrx',
        'q_clnt_civilStatx',
        'q_clnt_haveChildren',
        'q_clnt_shareToSpouse',
        'q_clnt_weddDate',
        'q_clnt_healthCondi',
        'q_clnt_healthCondiDetail',
        'q_clnt_takeRiskAssessM',
        'q_clnt_risk_cap',
        'q_clnt_risk_attix',
        'q_clnt_successfulDateSync',
        'q_clnt_lastLoggedIn',
        'q_clnt_TOKEN',
        'q_clnt_isActive',
        'q_clnt_addxDate',
        'q_clnt_updxDate',
    ];
}
