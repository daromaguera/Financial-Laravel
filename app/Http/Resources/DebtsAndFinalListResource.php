<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class DebtsAndFinalListResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $monthOnly = strtoupper(Carbon::parse($this->q_debtFin_dateCreated)->format('M'));
        return [
            'debFinList_id' => $this->q_debtFin_debFinList_id,
            'debFinList_description' => $this->q_debtFin_debFinList_desc,
            'isOtherCreated' => intval($this->q_debtFin_isOtherCreated) == 1 ? "Yes" : "No",
            'order' => $this->q_debtFin_order,
            'dateCreated' => Carbon::parse($this->q_debtFin_dateCreated)->format(strcmp($monthOnly,'MAY') == 0 ? 'd, M Y' : 'd, M. Y'),
            'Debts_And_Final_Expenses' => $this->q_debtFinExp_debFin_id ? [
                'debFin_id' => $this->q_debtFinExp_debFin_id,
                'client_id' => $this->q_debtFinExp_client_id,
                'debFinList_id' => $this->q_debtFinExp_debFinList_id,
                'amount_on_client' => $this->q_debtFinExp_amount_on_client,
                'amount_on_spouse' => $this->q_debtFinExp_amount_on_spouse,
            ] : []
        ];
    }
}
