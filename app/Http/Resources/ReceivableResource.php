<?php

namespace App\Http\Resources;

use App\Models\Heir;
use App\Http\Resources\HeirsResource;

use Illuminate\Http\Resources\Json\JsonResource;
use Carbon\Carbon;

class ReceivableResource extends JsonResource
{
    /**
     * Transform the resource into an array.  
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        $monthOnly1 = strtoupper(Carbon::parse($this->q_rec_dateUpdated)->format('M'));
        $monthOnly2 = strtoupper(Carbon::parse($this->q_rec_dateCreated)->format('M'));
        return [
            'receivables_id' => $this->q_rec_id,
            'client_id' => $this->q_rec_clientID,
            'name_of_debtor' => $this->q_rec_debtorName,
            'loan_purpose' => $this->q_rec_loanPurpose,
            'estimated_value' => $this->q_rec_estimatedValue,
            'percentage_collectibility' => $this->q_rec_percentCollectability,
            'exclusive_conjugal' => $this->q_rec_exclusiveConjugal,
            'share_self' => $this->q_rec_shareSelf,
            'share_spouse' => $this->q_rec_shareSpouse,
            'with_cli' => $this->q_rec_withCli,
            'renewal_month' => $this->q_rec_renewalMonth,
            'dateUpdated' => Carbon::parse($this->q_rec_dateUpdated)->format(strcmp($monthOnly1,'MAY') == 0 ? 'd, M Y' : 'd, M. Y'),
            'dateCreated' => Carbon::parse($this->q_rec_dateCreated)->format(strcmp($monthOnly2,'MAY') == 0 ? 'd, M Y' : 'd, M. Y'),
            'heirs' => HeirsResource::collection(Heir::where('q_heir_tableID',$this->q_rec_id)->where('q_heir_fromTable', 1)->get())
        ];
    }
}
