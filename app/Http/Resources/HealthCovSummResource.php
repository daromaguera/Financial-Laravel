<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class HealthCovSummResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        if(intval($this->q_healthCovSum_type) == 0){
            $data = [
                'first_name' => "Client",
                'last_name' => "Client",
                'middle_name' => "Client",
            ];
        }else{
            $data = [
                'first_name' => $this->q_famComp_firstName,
                'last_name' => $this->q_famComp_lastName,
                'middle_name' => $this->q_famComp_middleInitial,
            ];
        }
        $monthOnly1 = strtoupper(Carbon::parse($this->q_healthCovSum_dateUpdated)->format('M'));
        $monthOnly2 = strtoupper(Carbon::parse($this->q_healthCovSum_dateCreated)->format('M'));
        $data += [
            'healthCovSum_id' => $this->q_healthCovSum_id,
            'famComp_id' => $this->q_healthCovSum_famCompID,
            'type' => $this->q_healthCovSum_type,
            'policyRef_no' => $this->q_healthCovSum_policyRefNo,
            'origin' => $this->q_healthCovSum_origin,
            'amt_in_patient' => $this->q_healthCovSum_amtInPatient,
            'op_in_patient' => $this->q_healthCovSum_opInPatient,
            'amt_out_patient' => $this->q_healthCovSum_amtOutPatient,
            'op_out_patient' => $this->q_healthCovSum_opOutPatient,
            'amt_critical_illness_limit' => $this->q_healthCovSum_amtCritIllLim,
            'op_critical_illness_limit' => $this->q_healthCovSum_opCritIllLim,
            'amt_lab_limit' => $this->q_healthCovSum_amtLabLim,
            'amt_hosp_income' => $this->q_healthCovSum_amtHospIncome,
            'notes' => $this->q_healthCovSum_notes,
            'dateUpdated' => Carbon::parse($this->q_healthCovSum_dateUpdated)->format(strcmp($monthOnly1,'MAY') == 0 ? 'd, M Y' : 'd, M. Y'),
            'dateCreated' => Carbon::parse($this->q_healthCovSum_dateCreated)->format(strcmp($monthOnly2,'MAY') == 0 ? 'd, M Y' : 'd, M. Y'),
        ];
        return $data;
    }
}
