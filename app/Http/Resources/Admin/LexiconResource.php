<?php

namespace App\Http\Resources\Admin;

use Illuminate\Http\Resources\Json\JsonResource;

/**
 * Class StatementResource
 *
 * @OA\Schema(
 * )
 */
class LexiconResource extends JsonResource
{
    /**
     * @OA\Property(format="int64", title="ID", default=0, description="ID", property="id"),
     * @OA\Property(format="string", title="preferred_phrase", default="Default body", description="body of content", property="preferred_phrase"),
     * @OA\Property(format="string", title="guidance_for_usage", default="Default body guidance_for_usage", description="body of content guidance_for_usage", property="guidance_for_usage"),
     * @OA\Property(format="string", title="non_preferred_terms", default="Default body", description="body of content non_preferred_terms", property="non_preferred_terms"),
     */
    public function toArray($request): array
    {
        return parent::toArray($request);
    }
}
