<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class DreamsAspirationsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $monthOnly1 = strtoupper(Carbon::parse($this->q_dreAsp_dateUpdated)->format('M'));
        $monthOnly2 = strtoupper(Carbon::parse($this->q_dreAsp_dateCreated)->format('M'));
        return [
            'dreasp_id' => $this->q_dreAsp_id,
            'client_id' => $this->q_dreAsp_client_id,
            'goals' => $this->q_dreAsp_goals,
            'other_goals' => $this->q_dreAsp_otherGoals,
            'target_amount' => $this->q_dreAsp_typeTargetAmount,
            'timeline' => $this->q_dreAsp_timeline,
            'dateCreated' => Carbon::parse($this->q_dreAsp_dateUpdated)->format(strcmp($monthOnly1,'MAY') == 0 ? 'd, M Y' : 'd, M. Y'),
            'dateUpdated' => Carbon::parse($this->q_dreAsp_dateCreated)->format(strcmp($monthOnly2,'MAY') == 0 ? 'd, M Y' : 'd, M. Y'),
        ];
    }
}
