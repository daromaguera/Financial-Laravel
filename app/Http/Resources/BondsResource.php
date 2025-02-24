<?php

namespace App\Http\Resources;

use App\Models\Heir;
use App\Http\Resources\HeirsResource;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class BondsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $monthOnly1 = strtoupper(Carbon::parse($this->q_bond_dateUpdated)->format('M'));
        $monthOnly2 = strtoupper(Carbon::parse($this->q_bond_dateCreated)->format('M'));
        return [
            'bond_id' => $this->q_bond_id,
            'table_id' => $this->q_bond_id,
            'client_id' => $this->q_bond_clientID,
            'bonds_issuer' => $this->q_bond_issuer,
            'maturity_date' => $this->q_bond_maturityDate,
            'par_value' => $this->q_bond_perValue,
            'estimated_value' => $this->q_bond_estimatedValue,
            'purpose' => $this->q_bond_purpose,
            'with_guaranteed_payout' => $this->q_bond_withGuaranteedPayout,
            'exclusive_conjugal' => $this->q_bond_exclusiveConjugal,
            'share_self' => $this->q_bond_shareSelf,
            'share_spouse' => $this->q_bond_shareSpouse,
            
            'policyNo' => $this->q_bond_accNo,
            'insuProd' => $this->q_bond_insuProd,
            'projRate' => $this->q_bond_projRate,
            'projValEducAge' => $this->q_bond_projValEducAge,
            'from_table' => "Bonds",
            'type_of_account' => "",
            'regPayoutAmt' => $this->q_bond_regPayoutAmt,
            'ageStartPayout' => $this->q_bond_ageStartPayout,
            'startYearForPayout' => $this->q_bond_startYearForPayout,
            'freqOfPayout' => $this->q_bond_freqOfPayout,
            'ageChildForLastPayout' => $this->q_bond_ageChildForLastPayout,
            'endYearForPayout' => $this->q_bond_endYearForPayout,

            'dateCreated' => Carbon::parse($this->q_bond_dateUpdated)->format(strcmp($monthOnly1,'MAY') == 0 ? 'd, M Y' : 'd, M. Y'),
            'dateUpdated' => Carbon::parse($this->q_bond_dateCreated)->format(strcmp($monthOnly2,'MAY') == 0 ? 'd, M Y' : 'd, M. Y'),
            'heirs' => HeirsResource::collection(Heir::where('q_heir_tableID',$this->q_bond_id)->where('q_heir_fromTable', 4)->get())
        ];
    }
}
