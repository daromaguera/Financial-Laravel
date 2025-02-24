<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class EducPlannFNAResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $monthOnly1 = strtoupper(Carbon::parse($this->q_educPFNA_dateUpdated)->format('M'));
        $monthOnly2 = strtoupper(Carbon::parse($this->q_educPFNA_dateCreated)->format('M'));
        return [
            'educPlanFNA_id' => $this->q_educPFNA_id,
            'client_id' => $this->q_educPFNA_clientID,
            'reason_educPlan_important' => $this->q_educPFNA_resEducPlannImp,
            'dreams_for_children' => $this->q_educPFNA_dreamsForChildren,
            'dateUpdated' => Carbon::parse($this->q_educPFNA_dateUpdated)->format(strcmp($monthOnly1,'MAY') == 0 ? 'd, M Y' : 'd, M. Y'),
            'dateCreated' => Carbon::parse($this->q_educPFNA_dateCreated)->format(strcmp($monthOnly2,'MAY') == 0 ? 'd, M Y' : 'd, M. Y'),
        ];
    }
}
