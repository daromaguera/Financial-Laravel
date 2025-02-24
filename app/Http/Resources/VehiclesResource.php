<?php

namespace App\Http\Resources;

use App\Models\Heir;
use App\Http\Resources\HeirsResource;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class VehiclesResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $monthOnly1 = strtoupper(Carbon::parse($this->q_vehicle_dateUpdated)->format('M'));
        $monthOnly2 = strtoupper(Carbon::parse($this->q_vehicle_dateCreated)->format('M'));
        return [
            'vehicles_id' => $this->q_vehicle_id,
            'client_id' => $this->q_vehicle_clientID,
            'plate_no' => $this->q_vehicle_plateNo,
            'vehicle_type' => $this->q_vehicle_type,
            'estimated_value' => $this->q_vehicle_estimatedValue,
            'exclusive_conjugal' => $this->q_vehicle_exclusiveConjugal,
            'share_self' => $this->q_vehicle_shareSelf,
            'share_spouse' => $this->q_vehicle_shareSpouse,
            'with_vehicle_insurance' => $this->q_vehicle_withInsurance,
            'renewalMonth' => $this->q_vehicle_renewalMonth,

            'policyNo' => $this->q_vehicle_accNo,
            'insuProd' => $this->q_vehicle_insuProd,
            'projRate' => $this->q_vehicle_projRate,
            'projValEducAge' => $this->q_vehicle_projValEducAge,
            'from_table' => "Cash_And_Deposit",
            'type_of_account' => $this->q_vehicle_type,

            'dateUpdated' => Carbon::parse($this->q_vehicle_dateUpdated)->format(strcmp($monthOnly1,'MAY') == 0 ? 'd, M Y' : 'd, M. Y'),
            'dateCreated' => Carbon::parse($this->q_vehicle_dateCreated)->format(strcmp($monthOnly2,'MAY') == 0 ? 'd, M Y' : 'd, M. Y'),
            'heirs' => HeirsResource::collection(Heir::where('q_heir_tableID',$this->q_vehicle_id)->where('q_heir_fromTable', 7)->get())
        ];
    }
}
