<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

/**
 * @OA\Schema(),
 * required={"name","therapy_area_id"}
 *
 * Category Class
 */
class Category extends Model
{
    use HasFactory;

    /**
     * @OA\Property(format="string", title="name", default="Category Name", description="name", property="name"),
     * @OA\Property(format="int64", title="therapy_area_id", default="1", description="therapy_area_id", property="therapy_area_id"),
     */
    use SoftDeletes;

    public $table = 'categories';

    protected $fillable = [
        'name',
        'therapy_area_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function therapy_area(): BelongsTo
    {
        return $this->belongsTo(TherapyArea::class, 'therapy_area_id');
    }

    public function getCategoryById(int $id): array
    {
        return DB::table('categories')
            ->where('id', '=', $id)
            ->select('id', 'name', 'created_at', 'updated_at')
            ->get()->toArray();
    }

    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }
}
