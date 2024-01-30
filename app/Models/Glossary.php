<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Spatie\Image\Exceptions\InvalidManipulation;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * @OA\Schema(),
 * required={"name","therapy_area_id"}
 *
 * Statement Class
 */
class Glossary extends Model implements HasMedia
{
    use HasFactory;

    use InteractsWithMedia;
    /**
     * @OA\Property(format="int64", title="ID", default=0, description="ID", property="id"),
     * @OA\Property(format="string", title="name", default="term", description="name", property="name"),
     * @OA\Property(format="string", title="definition", default="term", description="definition", property="definition"),
     * @OA\Property(format="int64", title="therapy_area_id", default="1", description="therapy_area_id", property="therapy_area_id"),
     * @OA\Property(format="string", title="created_at", default="2022-01-06 15:47:41", description="created_at", property="created_at"),
     * @OA\Property(format="string", title="updated_at", default="2022-01-06 15:47:41", description="updated_at", property="updated_at"),
     * @OA\Property(format="string", title="deleted_at", default="2022-01-06 15:47:41", description="deleted_at", property="deleted_at"),
     */
    use SoftDeletes;

    public $table = 'glossaries';

    protected $fillable = [
        'term',
        'definition',
        'therapy_area_id',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    /**
     * @throws InvalidManipulation
     */
    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')->fit('crop', 50, 50);
        $this->addMediaConversion('preview')->fit('crop', 120, 120);
    }

    public function therapy_area(): BelongsTo
    {
        return $this->belongsTo(TherapyArea::class, 'therapy_area_id');
    }

    public function getAllGlossary(): LengthAwarePaginator
    {
        return DB::table('glossaries')
            ->whereNull('deleted_at')
            ->select('id', 'term', 'definition', 'therapy_area_id', 'created_at', 'updated_at', 'order_by')
            ->orderBy('order_by')
            ->paginate(500);
    }

    public function getAllGlossaryById($id): Collection
    {
        return DB::table('glossaries')
            ->whereNull('deleted_at')
            ->where('id', $id)
            ->select('id', 'term', 'definition', 'therapy_area_id', 'created_at', 'updated_at', 'order_by')
            ->orderBy('order_by')
            ->get();
    }

    public function createNewCategory($data): string
    {
        DB::table('glossaries')->insert($data);
        //$data->save();

        return DB::getPdo()->lastInsertId();
    }

    public function updateGlossaryById(int $id, $glossary): int
    {
        return DB::table('glossaries')
            ->where('id', $id)
            ->update($glossary);
    }

    public function getGlossaryByTherapyArea($therapy_area_id): LengthAwarePaginator
    {
        return DB::table('glossaries')
            ->whereNull('deleted_at')
            ->where('therapy_area_id', '=', $therapy_area_id)
            ->select('id', 'term', 'definition', 'created_at', 'updated_at', 'therapy_area_id', 'order_by')
            ->orderBy('order_by')
            ->paginate(50);
    }

    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }
}
