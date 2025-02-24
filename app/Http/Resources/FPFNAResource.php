<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class FPFNAResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $monthOnly1 = strtoupper(Carbon::parse($this->q_fpfna_dateUpdated)->format('M'));
        $monthOnly2 = strtoupper(Carbon::parse($this->q_fpfna_dateCreated)->format('M'));
        return [
            'fpfna_id' => $this->q_fpfna_id,
            'client_id' => $this->q_fpfna_clientID,
            'family_protection_important' => $this->q_sfp_reason, // from Selected Financial Priorities
            'financial_impact_disceased' => $this->q_fpfna_finImpDeceased,
            'average_infla_rate' => $this->q_fpfna_avgInflaRate,
            'annual_outflows_cl' => $this->q_fpfna_annOutflowsCL,
            'annual_outflows_sp' => $this->q_fpfna_annOutflowsSP,
            'years_family_support' => $this->q_fpfna_yearsFamSupp,
            'annual_support_from_cl' => $this->q_fpfna_annSuppFromCL,
            'annual_support_from_sp' => $this->q_fpfna_annSuppFromSP,
            'years_support_cl' => $this->q_fpfna_yearsSuppCL,
            'years_support_sp' => $this->q_fpfna_yearsSuppSP,
            'addx_life_insurance_cl' => $this->q_fpfna_addxLifeInsuCL,
            'addx_life_insurance_sp' => $this->q_fpfna_addxLifeInsuSP,
            'dateUpdated' => Carbon::parse($this->q_fpfna_dateUpdated)->format(strcmp($monthOnly1,'MAY') == 0 ? 'd, M Y' : 'd, M. Y'),
            'dateCreated' => Carbon::parse($this->q_fpfna_dateCreated)->format(strcmp($monthOnly2,'MAY') == 0 ? 'd, M Y' : 'd, M. Y'),
        ];
    }
}
