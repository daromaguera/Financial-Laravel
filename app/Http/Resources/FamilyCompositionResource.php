<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

use App\Models\SelectedChildEducPlan;
use App\Http\Resources\ChildEducPlanResource;

class FamilyCompositionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $monthOnly1 = strtoupper(Carbon::parse($this->q_famComp_dateUpdated)->format('M'));
        $monthOnly2 = strtoupper(Carbon::parse($this->q_famComp_dateCreated)->format('M'));
        $birthDate = Carbon::parse($this->q_famComp_birthDay);
        $age = $birthDate->diffInYears(Carbon::now());
        return [
            'fc_id' => $this->q_famComp_id,
            'client_id' => $this->q_famComp_clientID,
            'first_name' => $this->q_famComp_firstName,
            'last_name' => $this->q_famComp_lastName,
            'middle_initial' => $this->q_famComp_middleInitial,
            'type' => $this->q_famComp_compType,
            'withWithout_children' => $this->q_famComp_withWithoutChildren,
            'date_married' => $this->q_famComp_dateMarried ? Carbon::parse($this->q_famComp_dateMarried)->format('d, M. Y') : '',
            'birthday' => $this->q_famComp_birthDay,
            'age' => $age,
            'health_condition' => $this->q_famComp_healthCondition,
            'status' => $this->q_famComp_status,
            'revocable_living' => $this->q_famComp_revocableLiving,
            'revocable_last' => $this->q_famComp_revocableLast,
            'selected_child_info' => ChildEducPlanResource::collection(SelectedChildEducPlan::where('q_selChildEduP_famComp_id', $this->q_famComp_id)->get()),
            'dateUpdated' => Carbon::parse($this->q_famComp_dateUpdated)->format(strcmp($monthOnly1,'MAY') == 0 ? 'd, M Y' : 'd, M. Y'),
            'dateCreated' => Carbon::parse($this->q_famComp_dateCreated)->format(strcmp($monthOnly2,'MAY') == 0 ? 'd, M Y' : 'd, M. Y')
        ];
    }
}
