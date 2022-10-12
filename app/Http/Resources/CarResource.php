<?php

namespace App\Http\Resources;

use App\Models\Car;
use Illuminate\Http\Resources\Json\JsonResource;

class CarResource extends JsonResource
{
    public function toArray($request): array
    {
        /** @var Car $this */
        return [
            'id' => $this->id,
            'name' => $this->name,
            'driver' => $this->currentUser(),
        ];
    }
}
