<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class RecommendResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $monthOnly = strtoupper(Carbon::parse($this->q_recommx_dateCreated)->format('M'));
        return [
            'recommendation_id' => $this->q_recommx_id,
            'cfa_id' => $this->q_recommx_cfa_id,
            'description' => $this->q_recommx_recommxDetails,
            'inflow_outflow' => $this->q_recommx_isInflowOutflow,
            'dateCreated' => Carbon::parse($this->q_recommx_dateCreated)->format(strcmp($monthOnly,'MAY') == 0 ? 'd, M Y' : 'd, M. Y'),
        ];
    }
}
