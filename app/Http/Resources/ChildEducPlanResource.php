<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class ChildEducPlanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $monthOnly1 = strtoupper(Carbon::parse($this->q_selChildEduP_dateUpdated)->format('M'));
        $monthOnly2 = strtoupper(Carbon::parse($this->q_selChildEduP_dateCreated)->format('M'));
        $birthDate = Carbon::parse($this->q_famComp_birthDay);
        $age = $birthDate->diffInYears(Carbon::now());
        return [
            'selected_child_id' => $this->q_selChildEduP_id,
            'famComp_id' => $this->q_selChildEduP_famComp_id,
            'first_name' => $this->q_famComp_firstName,
            'last_name' => $this->q_famComp_lastName,
            'middle_initial' => $this->q_famComp_middleInitial,
            'child_birthday' => $this->q_famComp_birthDay,
            'age' => $age,
            'desired_school' => $this->q_selChildEduP_desiredSchool,
            'age_for_college' => $this->q_selChildEduP_childAgeCollege,
            'total_educ_fund_needed' => $this->q_selChildEduP_totalEducFundNeeded,
            'investment_alloc' => $this->q_selChildEduP_investmentAlloc,
            'dateUpdated' => Carbon::parse($this->q_selChildEduP_dateUpdated)->format(strcmp($monthOnly1,'MAY') == 0 ? 'd, M Y' : 'd, M. Y'),
            'dateCreated' => Carbon::parse($this->q_selChildEduP_dateCreated)->format(strcmp($monthOnly2,'MAY') == 0 ? 'd, M Y' : 'd, M. Y'),
        ];
    }
}
