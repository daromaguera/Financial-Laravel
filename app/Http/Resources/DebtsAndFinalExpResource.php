<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class DebtsAndFinalExpResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $monthOnly1 = strtoupper(Carbon::parse($this->q_debtFinExp_dateUpdated)->format('M'));
        $monthOnly2 = strtoupper(Carbon::parse($this->q_debtFinExp_dateCreated)->format('M'));
        return [
            'debFin_id' => $this->q_debtFinExp_debFin_id,
            'client_id' => $this->q_debtFinExp_client_id,
            'debFinList_id' => $this->q_debtFinExp_debFinList_id,
            'amount_on_client' => $this->q_debtFinExp_amount_on_client,
            'amount_on_spouse' => $this->q_debtFinExp_amount_on_spouse,
            'dateUpdated' => Carbon::parse($this->q_debtFinExp_dateUpdated)->format(strcmp($monthOnly1,'MAY') == 0 ? 'd, M Y' : 'd, M. Y'),
            'dateCreated' => Carbon::parse($this->q_debtFinExp_dateCreated)->format(strcmp($monthOnly2,'MAY') == 0 ? 'd, M Y' : 'd, M. Y'),
        ];
    }
}
