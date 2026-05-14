<?php

namespace App\Http\Resources;

use App\Enums\UserRoleEnum;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;


/**
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $profile_photo_url
 * @property UserRoleEnum $roles
 * @property bool status
 */
class UserResource extends JsonResource
{

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {

        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'photo_url' => $this->profile_photo_url,
//            'role' => $this->roles->pluck('name')->first(),
            'status' => $this->status,

        ];

    }
}
