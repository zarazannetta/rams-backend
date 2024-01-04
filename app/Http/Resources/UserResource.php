<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'email' => $this->email,
            'username' => $this->username,
            'fullname' => $this->fullname,
            'role' => $this->role->role,
            'joined' => date('M jS, Y', strtotime($this->created_at)),
            'avatar' => $this->avatar
        ];
    }
}
