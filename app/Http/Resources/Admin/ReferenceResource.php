<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class ResourceResource
 *
 * @OA\Schema(
 * )
 */
class ReferenceResource extends JsonResource
{
    /**
     * @OA\Property(format="int64", title="ID", default=0, description="ID", property="id"),
     * @OA\Property(format="string", title="title", default="File title", description="name", property="title"),
     * @OA\Property(format="string", title="file_name", default="https://awsS3.com", description="file_name", property="file_name")
     * @OA\Property(format="string", title="url", default="https://www.google.com", description="url", property="url")
     */
    public function toArray($request): array
    {
        return parent::toArray($request);
    }
}
