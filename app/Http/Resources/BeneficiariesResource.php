<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class BeneficiariesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $monthOnly1 = strtoupper(Carbon::parse($this->q_benex_dateUpdated)->format('M'));
        $monthOnly2 = strtoupper(Carbon::parse($this->q_benex_dateCreated)->format('M'));
        return [
            'beneficiaries_id' => $this->q_benex_id,
            'full_name' => $this->q_benex_fullName,
            'percent_share' => $this->q_benex_percentShare,
            'designation' => $this->q_benex_designation,
            'priority' => $this->q_benex_priority,
            'dateUpdated' => Carbon::parse($this->q_benex_dateUpdated)->format(strcmp($monthOnly1,'MAY') == 0 ? 'd, M Y' : 'd, M. Y'),
            'dateCreated' => Carbon::parse($this->q_benex_dateCreated)->format(strcmp($monthOnly2,'MAY') == 0 ? 'd, M Y' : 'd, M. Y')
        ];
    }
}
