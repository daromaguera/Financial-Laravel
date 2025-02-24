<?php

namespace App\Http\Resources;

use App\Models\Heir;
use App\Http\Resources\HeirsResource;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class PersonalAssetsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $monthOnly1 = strtoupper(Carbon::parse($this->q_perAs_dateUpdated)->format('M'));
        $monthOnly2 = strtoupper(Carbon::parse($this->q_perAs_dateCreated)->format('M'));
        return [
            'pa_id' => $this->q_perAs_id,
            'table_id' => $this->q_perAs_id,
            'client_id' => $this->q_perAs_clientID,
            'item_name' => $this->q_perAs_item,
            'estimated_value' => $this->q_perAs_estimatedValue,  
            'purpose' => $this->q_perAs_purpose,
            'with_guaranteed_payout' => $this->q_perAs_withGuaranteedPayout,
            'exclusive_conjugal' => $this->q_perAs_exclusiveConjugal,
            'share_self' => $this->q_perAs_shareSelf,
            'share_spouse' => $this->q_perAs_shareSpouse,

            'policyNo' => $this->q_perAs_accNo,
            'insuProd' => $this->q_perAs_insuProd,
            'projRate' => $this->q_perAs_projRate,
            'projValEducAge' => $this->q_perAs_projValEducAge,
            'from_table' => "Personal_Assets",
            'type_of_account' => "",
            'regPayoutAmt' => $this->q_perAs_regPayoutAmt,
            'ageStartPayout' => $this->q_perAs_ageStartPayout,
            'startYearForPayout' => $this->q_perAs_startYearForPayout,
            'freqOfPayout' => $this->q_perAs_freqOfPayout,
            'ageChildForLastPayout' => $this->q_perAs_ageChildForLastPayout,
            'endYearForPayout' => $this->q_perAs_endYearForPayout,
            
            'dateUpdated' => Carbon::parse($this->q_perAs_dateUpdated)->format(strcmp($monthOnly1,'MAY') == 0 ? 'd, M Y' : 'd, M. Y'),
            'dateCreated' => Carbon::parse($this->q_perAs_dateCreated)->format(strcmp($monthOnly2,'MAY') == 0 ? 'd, M Y' : 'd, M. Y'),
            'heirs' => HeirsResource::collection(Heir::where('q_heir_tableID',$this->q_perAs_id)->where('q_heir_fromTable', 8)->get())
        ];
    }
}
