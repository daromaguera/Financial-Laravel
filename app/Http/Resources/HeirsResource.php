<?php

namespace App\Http\Resources;

use App\Models\FamilyComposition;
use App\Http\Resources\FamilyCompositionResource;

use Illuminate\Http\Resources\Json\JsonResource;

class HeirsResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        return [
            'heir_id' => $this->q_heir_id,
            'famComp_id' => $this->q_heir_famComp_id,
            'table_ID' => $this->q_heir_tableID,
            'from_table' => $this->q_heir_fromTable,
            'indicated_percent' => $this->q_heir_indicatedPercentage,
            'Family_Composition' => FamilyCompositionResource::collection(FamilyComposition::where('q_famComp_id',$this->q_heir_famComp_id)->get())
        ];
    }
}
