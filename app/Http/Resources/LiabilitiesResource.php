<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class LiabilitiesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $monthOnly1 = strtoupper(Carbon::parse($this->q_lia_dateUpdated)->format('M'));
        $monthOnly2 = strtoupper(Carbon::parse($this->q_lia_dateCreated)->format('M'));
        return [
            'liabilities_id' => $this->q_lia_id,
            'client_id' => $this->q_lia_clientID,
            'name_of_creditor' => $this->q_lia_creditorName,   
            'type_of_liability' => $this->q_lia_type,  
            'total_unpaid_amount' => $this->q_lia_totalUnpaidAmt,
            'annual_interest_rate' => $this->q_lia_annualInterestRate,
            'amount_of_mri' => $this->q_lia_amtOfMRI,
            'amount_uncovered' => $this->q_lia_uncovered,
            'exclusive_conjugal' => $this->q_lia_exclusiveConjugal,
            'share_self' => $this->q_lia_shareSelf,
            'share_spouse' => $this->q_lia_shareSpouse,
            'dateUpdated' => Carbon::parse($this->q_lia_dateUpdated)->format(strcmp($monthOnly1,'MAY') == 0 ? 'd, M Y' : 'd, M. Y'),
            'dateCreated' => Carbon::parse($this->q_lia_dateCreated)->format(strcmp($monthOnly2,'MAY') == 0 ? 'd, M Y' : 'd, M. Y')
        ];
    }
}
