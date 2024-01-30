<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class UserResource
 *
 * @OA\Schema(
 * )
 */
class UserResource extends JsonResource
{
    /**
     * @OA\Property(format="int64", title="ID", default=1, description="ID", property="id"),
     * @OA\Property(format="string", title="name", default="firstname", description="name", property="name"),
     * @OA\Property(format="string", title="email", default="admin@synaptikdigital.com", description="email", property="email")
     */
    public function toArray($request)
    {
        return parent::toArray($request);
    }
}
