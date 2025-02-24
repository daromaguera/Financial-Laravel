<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class FinancialPrioritiesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $monthOnly = strtoupper(Carbon::parse($this->q_fp_dateCreated)->format('M'));
        return [
            'fplist_id' => $this->q_fp_id,
            'name' => $this->q_fp_name,
            'description' => $this->q_fp_descripx,
            'dateCreated' => Carbon::parse($this->q_fp_dateCreated)->format(strcmp($monthOnly,'MAY') == 0 ? 'd, M Y' : 'd, M. Y'),
        ];
    }
}
