<?php

namespace App\Http\Resources;

use App\Models\Beneficiaries;
use App\Http\Resources\BeneficiariesResource;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class LifeAndHealthInsuranceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $monthOnly1 = strtoupper(Carbon::parse($this->q_lifeHealth_dateUpdated)->format('M'));
        $monthOnly2 = strtoupper(Carbon::parse($this->q_lifeHealth_dateCreated)->format('M'));
        return [
            'flahi_id' => $this->q_lifeHealth_id,
            'table_id' => $this->q_lifeHealth_id,
            'client_id' => $this->q_lifeHealth_clientID,
            'insurance_company' => $this->q_lifeHealth_insuranceCompany,
            'policy_owner' => $this->q_lifeHealth_policyOwner,
            'policy_number' => $this->q_lifeHealth_policyNumber,
            'type_of_policy' => $this->q_lifeHealth_typeOfPolicy,
            'month_year_issued' => $this->q_lifeHealth_monthYearIssued,
            'insured' => $this->q_lifeHealth_insured,
            'purpose' => $this->q_lifeHealth_purpose,
            'with_guaranteed_payout' => $this->q_lifeHealth_withGuaranteedPayout,
            'faceamount_fpcf' => $this->q_lifeHealth_faceAmountFamilyProtection,
            'faceamount_etax' => $this->q_lifeHealth_faceAmountEstateTax,
            'faceamount_edistribution' => $this->q_lifeHealth_faceAmountEstateDistribution,
            'faceamount_total' => $this->q_lifeHealth_faceAmount,
            'current_account_value' => $this->q_lifeHealth_currentFundValueEstimated,

            'policyNo' => $this->q_lifeHealth_accNo,
            'insuProd' => $this->q_lifeHealth_insuProd,
            'projRate' => $this->q_lifeHealth_projRate,
            'projValEducAge' => $this->q_lifeHealth_projValEducAge,
            'from_table' => $this->q_lifeHealth_fromAetosAdviser == 1 ? "Life_And_Health_Insurance_From_Aetos" : "Life_And_Health_Insurance",
            'type_of_account' => $this->q_lifeHealth_typeOfPolicy,
            'regPayoutAmt' => $this->q_lifeHealth_regPayoutAmt,
            'ageStartPayout' => $this->q_lifeHealth_ageStartPayout,
            'startYearForPayout' => $this->q_lifeHealth_startYearForPayout,
            'freqOfPayout' => $this->q_lifeHealth_freqOfPayout,
            'ageChildForLastPayout' => $this->q_lifeHealth_ageChildForLastPayout,
            'endYearForPayout' => $this->q_lifeHealth_endYearForPayout,
            
            'dateUpdated' => Carbon::parse($this->q_lifeHealth_dateUpdated)->format(strcmp($monthOnly1,'MAY') == 0 ? 'd, M Y' : 'd, M. Y'),
            'dateCreated' => Carbon::parse($this->q_lifeHealth_dateCreated)->format(strcmp($monthOnly2,'MAY') == 0 ? 'd, M Y' : 'd, M. Y'),
            'beneficiaries' => BeneficiariesResource::collection(Beneficiaries::where('q_benex_lifeHeath_id',$this->q_lifeHealth_id)->get())
        ];
    }
}
