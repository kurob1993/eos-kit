<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\Resource;
use App\Models\AbsenceApproval;

class AbsenceApprovalResource extends Resource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'absence' => AbsenceApproval::collection($this->whenLoaded('absence')),
            'regno' => $this->regno,
            'sequence' => $this->sequence,
            'status_id' => $this->status_id,
            'text' => $this->text,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
