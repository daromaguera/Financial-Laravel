<?php

namespace App\Http\Resources;

use App\Models\Heir;
use App\Http\Resources\HeirsResource;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class CashAndDepositResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $monthOnly1 = strtoupper(Carbon::parse($this->q_cad_dateUpdated)->format('M'));
        $monthOnly2 = strtoupper(Carbon::parse($this->q_cad_dateCreated)->format('M'));
        return [
            'cad_id' => $this->q_cad_id,
            'table_id' => $this->q_cad_id,
            'client_ID' => $this->q_cad_clientID,
            'bank' => $this->q_cad_bank,
            'account_description' => $this->q_cad_accountDescription,
            'type_of_account' => $this->q_cad_typeOfAccount,
            'estimated_value' => $this->q_cad_estimatedValue,
            'purpose' => $this->q_cad_purpose,
            'with_guaranteed_payout' => $this->q_cad_withGuaranteedPayout,
            'exclusive_conjugal' => $this->q_cad_exclusiveConjugal,
            'share_self' => $this->q_cad_shareSelf,
            'share_spouse' => $this->q_cad_shareSpouse,

            'policyNo' => $this->q_cad_accNo,
            'insuProd' => $this->q_cad_insuProd,
            'projRate' => $this->q_cad_projRate,
            'projValEducAge' => $this->q_cad_projValEducAge,
            'from_table' => "Cash_And_Deposit",
            'regPayoutAmt' => $this->q_cad_regPayoutAmt,
            'ageStartPayout' => $this->q_cad_ageStartPayout,
            'startYearForPayout' => $this->q_cad_startYearForPayout,
            'freqOfPayout' => $this->q_cad_freqOfPayout,
            'ageChildForLastPayout' => $this->q_cad_ageChildForLastPayout,
            'endYearForPayout' => $this->q_cad_endYearForPayout,

            
            'dateCreated' => Carbon::parse($this->q_cad_dateUpdated)->format(strcmp($monthOnly1,'MAY') == 0 ? 'd, M Y' : 'd, M. Y'),
            'dateUpdated' => Carbon::parse($this->q_cad_dateCreated)->format(strcmp($monthOnly2,'MAY') == 0 ? 'd, M Y' : 'd, M. Y'),
            'heirs' => HeirsResource::collection(Heir::where('q_heir_tableID',$this->q_cad_id)->where('q_heir_fromTable', 2)->get())
        ];
    }
}
