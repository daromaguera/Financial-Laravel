<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SelectedChildEducPlan extends Model
{
    use HasFactory;

    const NAME  = 'SelectedChildEducPlan';
    protected $table = 'selected_child_educ_plans';
    public $timestamps = false;

    protected $fillable = [
        'q_selChildEduP_famComp_id',
        'q_selChildEduP_desiredSchool',
        'q_selChildEduP_childAgeCollege',
        'q_selChildEduP_totalEducFundNeeded',
        'q_selChildEduP_investmentAlloc',
        'q_selChildEduP_dateUpdated',
        'q_selChildEduP_dateCreated',
    ];
}
