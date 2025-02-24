<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class HealthFundPlannFNAResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $monthOnly1 = strtoupper(Carbon::parse($this->q_healthFP_dateUpdated)->format('M'));
        $monthOnly2 = strtoupper(Carbon::parse($this->q_healthFP_dateCreated)->format('M'));
        return [
            'healthFP_id' => $this->q_healthFP_id,
            'client_id' => $this->q_healthFP_clientID,
            'reason_health_fund' => $this->q_healthFP_resHealthFund,
            'financialSit_with_illMember' => $this->q_healthFP_finSitWithIllMember,
            'financial_impact' => $this->q_healthFP_finImpact,
            'dateUpdated' => Carbon::parse($this->q_healthFP_dateUpdated)->format(strcmp($monthOnly1,'MAY') == 0 ? 'd, M Y' : 'd, M. Y'),
            'dateCreated' => Carbon::parse($this->q_healthFP_dateCreated)->format(strcmp($monthOnly2,'MAY') == 0 ? 'd, M Y' : 'd, M. Y'),
        ];
    }
}
