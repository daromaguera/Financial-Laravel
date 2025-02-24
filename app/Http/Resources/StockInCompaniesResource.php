<?php

namespace App\Http\Resources;

use App\Models\Heir;
use App\Http\Resources\HeirsResource;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class StockInCompaniesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $monthOnly1 = strtoupper(Carbon::parse($this->q_stoComp_dateUpdated)->format('M'));
        $monthOnly2 = strtoupper(Carbon::parse($this->q_stoComp_dateCreated)->format('M'));
        return [
            'sic_id' => $this->q_stoComp_id,
            'table_id' => $this->q_stoComp_id,
            'client_id' => $this->q_stoComp_clientID,
            'company_alias' => $this->q_stoComp_companyAlias,
            'no_of_shares' => $this->q_stoComp_noOfShares,
            'current_book_value' => $this->q_stoComp_currentBookValueShare,
            'estimated_value' => $this->q_stoComp_estimatedValue,
            'purpose' => $this->q_stoComp_purpose,
            'exclusive_conjugal' => $this->q_stoComp_exclusiveConjugal,
            'share_self' => $this->q_stoComp_shareSelf,
            'share_spouse' => $this->q_stoComp_shareSpouse,
            'listed_nonListed' => $this->q_stoComp_isListed,
            
            'policyNo' => $this->q_stoComp_accNo,
            'insuProd' => $this->q_stoComp_insuProd,
            'projRate' => $this->q_stoComp_projRate,
            'projValEducAge' => $this->q_stoComp_projValEducAge,
            'from_table' => $this->q_stoComp_isListed == 1 ? "Stock_In_Listed_Companies" : "Stock_In_NonListed_Companies",
            'type_of_account' => "",
            'regPayoutAmt' => $this->q_stoComp_regPayoutAmt,
            'ageStartPayout' => $this->q_stoComp_ageStartPayout,
            'startYearForPayout' => $this->q_stoComp_startYearForPayout,
            'freqOfPayout' => $this->q_stoComp_freqOfPayout,
            'ageChildForLastPayout' => $this->q_stoComp_ageChildForLastPayout,
            'endYearForPayout' => $this->q_stoComp_endYearForPayout,

            'dateUpdated' => Carbon::parse($this->q_stoComp_dateUpdated)->format(strcmp($monthOnly1,'MAY') == 0 ? 'd, M Y' : 'd, M. Y'),
            'dateCreated' => Carbon::parse($this->q_stoComp_dateCreated)->format(strcmp($monthOnly2,'MAY') == 0 ? 'd, M Y' : 'd, M. Y'),
            'heirs' => HeirsResource::collection(Heir::where('q_heir_tableID',$this->q_stoComp_id)->where('q_heir_fromTable', 5)->get())
        ];
    }
}
