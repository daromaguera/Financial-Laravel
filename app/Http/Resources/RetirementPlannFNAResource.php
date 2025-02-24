<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class RetirementPlannFNAResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $monthOnly1 = strtoupper(Carbon::parse($this->q_retPFNA_dateUpdated)->format('M'));
        $monthOnly2 = strtoupper(Carbon::parse($this->q_retPFNA_dateCreated)->format('M'));
        return [
            'retPFNA_id' => $this->q_retPFNA_id,
            'client_id' => $this->q_retPFNA_clientID,
            'reason_retirement_plann' => $this->q_retPFNAa_resRetPlann,
            'how_retirement_looks' => $this->q_retPFNA_howRetLooks,
            'current_age_cl' => $this->q_retPFNA_currAgeCL,
            'current_age_sp' => $this->q_retPFNA_currAgeSP,
            'age_retirement_cl' => $this->q_retPFNA_ageRetCL,
            'age_retirement_sp' => $this->q_retPFNA_ageRetSP,
            'life_span_cl' => $this->q_retPFNA_lifeSpanCL,
            'life_span_sp' => $this->q_retPFNA_lifeSpanSP,
            'avg_inflation_rate' => $this->q_retPFNA_avgInfaRate,
            'interest_retirement' => $this->q_retPFNA_intRetirement,
            'sss_anual_cl' => $this->q_retPFNA_sssAnnualCL,
            'sss_anual_sp' => $this->q_retPFNA_sssAnnualSP,
            'yrs_sss_benefit_cl' => $this->q_retPFNA_yrsSSSBenefitCL,
            'yrs_sss_benefit_sp' => $this->q_retPFNA_yrsSSSBenefitSP,
            'comp_benefit_ret_cl' => $this->q_retPFNA_companyBenefitRetCL,
            'comp_benefit_ret_sp' => $this->q_retPFNA_companyBenefitRetSP,
            'yrs_comp_benefit_cl' => $this->q_retPFNA_yrsCompanyBenefitCL,
            'yrs_comp_benefit_sp' => $this->q_retPFNA_yrsCompanyBenefitSP,
            'dateUpdated' => Carbon::parse($this->q_retPFNA_dateUpdated)->format(strcmp($monthOnly1,'MAY') == 0 ? 'd, M Y' : 'd, M. Y'),
            'dateCreated' => Carbon::parse($this->q_retPFNA_dateCreated)->format(strcmp($monthOnly2,'MAY') == 0 ? 'd, M Y' : 'd, M. Y'),
        ];
    }
}
