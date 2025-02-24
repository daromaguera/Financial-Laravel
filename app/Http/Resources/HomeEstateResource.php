<?php

namespace App\Http\Resources;

use App\Models\Heir;
use App\Http\Resources\HeirsResource;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class HomeEstateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $monthOnly1 = strtoupper(Carbon::parse($this->q_homeEstate_dateUpdated)->format('M'));
        $monthOnly2 = strtoupper(Carbon::parse($this->q_homeEstate_dateCreated)->format('M'));
        return [
            'fh_id' => $this->q_homeEstate_id,
            'table_id' => $this->q_homeEstate_id,
            'client_id' => $this->q_homeEstate_clientID,
            'tct_cct_number' => $this->q_homeEstate_tctNumber,
            'location' => $this->q_homeEstate_cityMunLocation,
            'area_sqm' => $this->q_homeEstate_areaSQM,
            'bir_zonal_value' => $this->q_homeEstate_zoneValueEstimate,
            'estimated_value' => $this->q_homeEstate_estimatedValue,
            'exclusive_conjugal' => $this->q_homeEstate_exclusiveConjugal,
            'purpose' => $this->q_homeEstate_purpose,
            'with_guaranteed_payout' => $this->q_homeEstate_withGuaranteedPayout,
            'share_self' => $this->q_homeEstate_shareSelf,
            'share_spouse' => $this->q_homeEstate_shareSpouse,
            'with_property_insurance' => $this->q_homeEstate_withPropertyInsurance,
            'renewalMonth' => $this->q_homeEstate_renewalMonth,
            'familyHome_realState' => $this->q_homeEstate_isHome,

            'policyNo' => $this->q_homeEstate_accNo,
            'insuProd' => $this->q_homeEstate_insuProd,
            'projRate' => $this->q_homeEstate_projRate,
            'projValEducAge' => $this->q_homeEstate_projValEducAge,
            'from_table' => $this->q_homeEstate_isHome == 1 ? "Family_Home" : "Real_Estate",
            'type_of_account' => "",
            'regPayoutAmt' => $this->q_homeEstate_regPayoutAmt,
            'ageStartPayout' => $this->q_homeEstate_ageStartPayout,
            'startYearForPayout' => $this->q_homeEstate_startYearForPayout,
            'freqOfPayout' => $this->q_homeEstate_freqOfPayout,
            'ageChildForLastPayout' => $this->q_homeEstate_ageChildForLastPayout,
            'endYearForPayout' => $this->q_homeEstate_endYearForPayout,
            
            'dateUpdated' => Carbon::parse($this->q_homeEstate_dateUpdated)->format(strcmp($monthOnly1,'MAY') == 0 ? 'd, M Y' : 'd, M. Y'),
            'dateCreated' => Carbon::parse($this->q_homeEstate_dateCreated)->format(strcmp($monthOnly2,'MAY') == 0 ? 'd, M Y' : 'd, M. Y'),
            'heirs' => HeirsResource::collection(Heir::where('q_heir_tableID',$this->q_homeEstate_id)->where('q_heir_fromTable', 6)->get())
        ];
    }
}
