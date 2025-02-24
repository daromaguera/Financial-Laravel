<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class EducPExpListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $monthOnly1 = strtoupper(Carbon::parse($this->q_educPExpList_dateCreated)->format('M'));
        $monthOnly2 = strtoupper(Carbon::parse($this->q_educPExp_dateCreated)->format('M'));
        return [
            'educExpList_id' => $this->q_educPExpList_id,
            'description' => $this->q_educPExpList_description,
            'isOther' => intval($this->q_educPExpList_isOther) == 1 ? "Yes" : "No",
            'dateCreated' => Carbon::parse($this->q_educPExpList_dateCreated)->format(strcmp($monthOnly1,'MAY') == 0 ? 'd, M Y' : 'd, M. Y'),
            'order' => $this->q_educPExpList_order,
            'Education_Planning_Expenses' => $this->q_educPExp_id ? [
                'educPlanExp_id' => $this->q_educPExp_id,
                'familyComp_id' => $this->q_educPExp_famComp_id,
                'educPlanExpList_id' => $this->q_educPExp_educPExpList_id,
                'presentVal_amt' => $this->q_educPExp_presentValAmt,
                'avg_inflation_rate' => $this->q_educPExp_avgInflationRate,
                'fundNeeded_futureVal_amt' => $this->q_educPExp_futureNeededValAmt,
                'dateCreated' => Carbon::parse($this->q_educPExp_dateCreated)->format(strcmp($monthOnly2,'MAY') == 0 ? 'd, M Y' : 'd, M. Y'),
            ] : []
        ];
    }
}
