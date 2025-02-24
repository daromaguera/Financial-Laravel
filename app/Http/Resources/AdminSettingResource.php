<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class AdminSettingResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $monthOnly1 = strtoupper(Carbon::parse($this->q_admSett_dateUpdated)->format('M'));
        $monthOnly2 = strtoupper(Carbon::parse($this->q_admSett_dateCreated)->format('M'));
        return [
            'famProInflaRate' => $this->q_admSett_famProInflaRate,
            'retInflationRate' => $this->q_admSett_retInflationRate,
            'retEstInterestRate' => $this->q_admSett_retEstInterestRate,
            'childEducInflaRate' => $this->q_admSett_childEducInflaRate,
            'estateConvCurrTaxRate' => $this->q_admSett_estateConvCurrTaxRate,
            'estateConvOtherExpenses' => $this->q_admSett_estateConvOtherExpenses,
            'ageChildGoCollege' => $this->q_admSett_ageChildGoCollege,
            'dateCreated' => Carbon::parse($this->q_admSett_dateUpdated)->format(strcmp($monthOnly1,'MAY') == 0 ? 'd, M Y' : 'd, M. Y'),
            'dateUpdated' => Carbon::parse($this->q_admSett_dateCreated)->format(strcmp($monthOnly2,'MAY') == 0 ? 'd, M Y' : 'd, M. Y'),
        ];
    }
}
