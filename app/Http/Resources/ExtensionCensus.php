<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ExtensionCensus extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
      // return parent::toArray($request);
      return [
        'id'          => $this->id,
        'name'        => $this->name,
        'pets_count'  => $this->pets_count,
        'deposit'     => $this->deposit,
        'owner_phone' => $this->owner_phone,
        'phone_1'     => $this->phone_1,
        'phone_2'     => $this->phone_2,
      ];
    }
}
