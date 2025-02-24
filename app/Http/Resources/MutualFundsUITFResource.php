<?php

namespace App\Http\Resources;

use App\Models\Heir;
use App\Http\Resources\HeirsResource;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class MutualFundsUITFResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $monthOnly1 = strtoupper(Carbon::parse($this->q_uitf_dateUpdated)->format('M'));
        $monthOnly2 = strtoupper(Carbon::parse($this->q_uitf_dateCreated)->format('M'));
        return [
            'mfuitf_id' => $this->q_uitf_id,
            'table_id' => $this->q_uitf_id,
            'client_id' => $this->q_uitf_clientID,
            'company' => $this->q_uitf_company,
            'no_of_units' => $this->q_uitf_noOfUnits,      
            'current_value' => $this->q_uitf_currentValuePerUnits,
            'estimated_value' => $this->q_uitf_estimatedValue,
            'purpose' => $this->q_uitf_purpose,
            'with_guaranteed_payout' => $this->q_uitf_withGuaranteedPayout,
            'exclusive_conjugal' => $this->q_uitf_exclusiveConjugal,
            'share_self' => $this->q_uitf_shareSelf,
            'share_spouse' => $this->q_uitf_shareSpouse,
            
            'policyNo' => $this->q_uitf_accNo,
            'insuProd' => $this->q_uitf_insuProd,
            'projRate' => $this->q_uitf_projRate,
            'projValEducAge' => $this->q_uitf_projValEducAge,
            'from_table' => "MFUITF",
            'type_of_account' => "",
            'regPayoutAmt' => $this->q_uitf_regPayoutAmt,
            'ageStartPayout' => $this->q_uitf_ageStartPayout,
            'startYearForPayout' => $this->q_uitf_startYearForPayout,
            'freqOfPayout' => $this->q_uitf_freqOfPayout,
            'ageChildForLastPayout' => $this->q_uitf_ageChildForLastPayout,
            'endYearForPayout' => $this->q_uitf_endYearForPayout,

            'dateCreated' => Carbon::parse($this->q_uitf_dateUpdated)->format(strcmp($monthOnly1,'MAY') == 0 ? 'd, M Y' : 'd, M. Y'),
            'dateUpdated' => Carbon::parse($this->q_uitf_dateCreated)->format(strcmp($monthOnly2,'MAY') == 0 ? 'd, M Y' : 'd, M. Y'),
            'heirs' => HeirsResource::collection(Heir::where('q_heir_tableID',$this->q_uitf_id)->where('q_heir_fromTable', 3)->get())
        ];
    }
}
