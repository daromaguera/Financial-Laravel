<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ExistingLifeInsuranceCoverageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'exLifeInsCov_id' => $this->q_exLifeInsCov_id,
            'client_id' => $this->q_exLifeInsCov_clientID,
            'exLifeInsCovList_id' => $this->q_exLifeInsCov_listID,
            'amount_on_client' => $this->q_exListInsCov_amtClient,
            'amount_on_spouse' => $this->q_exListInsCov_amtSpouse,
        ];
    }
}
