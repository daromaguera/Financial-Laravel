<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class LifeInsuranceCoverageListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $monthOnly = strtoupper(Carbon::parse($this->q_lifeInsCovList_dateCreated)->format('M'));
        return [
            'debFinList_id' => $this->q_lifeInsCovList_id,
            'debFinList_description' => $this->q_lifeInsCovList_debFinListDesc,
            'isOtherCreated' => intval($this->q_lifeInsCovList_isOtherCreated) == 1 ? "Yes" : "No",
            'order' => $this->q_lifeInsCovList_order,
            'dateCreated' => Carbon::parse($this->q_lifeInsCovList_dateCreated)->format(strcmp($monthOnly,'MAY') == 0 ? 'd, M Y' : 'd, M. Y'),
            'Existing_Life_Insurance_Coverage' => $this->q_exLifeInsCov_id ? [
                'exLifeInsCov_id' => $this->q_exLifeInsCov_id,
                'client_id' => $this->q_exLifeInsCov_clientID,
                'exLifeInsCovList_id' => $this->q_exLifeInsCov_listID,
                'amount_on_client' => $this->q_exLifeInsCov_amtClient,
                'amount_on_spouse' => $this->q_exLifeInsCov_amtSpouse,
            ] : []
        ];
    }
}
