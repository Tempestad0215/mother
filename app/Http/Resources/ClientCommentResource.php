<?php

namespace App\Http\Resources;

use App\Enums\ClientTypeEnum;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/**
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string $phone
 * @property string $personal_id
 * @property string $email
 * @property string $address
 * @property boolean $status
 * @property ClientTypeEnum $type
 * @property string $created_at
 * @property string $updated_at
 * @property Comment $comment
 */
class ClientCommentResource extends JsonResource
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
            'code' => $this->code,
            'name' => $this->name,
            'personal_id' => $this->personal_id,
            'phone' => $this->phone,
            'email' => $this->email,
            'address' => $this->address,
            'type' => $this->type,
            'status' => $this->status,
            'comment' => [
                'id' => $this->comment->id,
                'content' => $this->comment->content,
                'created_at' => $this->comment->created_at,
            ],
            'created_at' => $this->created_at

        ];
    }


}
