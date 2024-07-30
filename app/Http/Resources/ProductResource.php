<?php

namespace App\Http\Resources;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $user = Auth::user();
        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'price_per_unit' => $this->price_per_unit,
            'basic_unit' => $this->basic_unit,
            'tax_percentage' => $this->tax_percentage,
            'limited' => $this->limited,
            'stock' => $this->stock,
            'manager_id' => $this->manager_id,
        ];

        if ($user->role === 0) {
            $data['active_for_sale'] = $this->active_for_sale;
        }

        return $data;
    }
}
