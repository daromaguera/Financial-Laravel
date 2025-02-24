<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class FnaCompletionResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $monthOnly = strtoupper(Carbon::parse($this->q_fnaComp_dateCreated)->format('M'));
        return [
            'compStatus_id' => $this->q_fnaComp_id,
            'FNA' => $this->q_fnaComp_FNA,
            'status_value' => $this->q_fnaComp_statusValue,
            'dateCreated' => Carbon::parse($this->q_fnaComp_dateCreated)->format(strcmp($monthOnly,'MAY') == 0 ? 'd, M Y' : 'd, M. Y'),
        ];
    }
}
