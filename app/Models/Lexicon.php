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
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * @OA\Schema(),
 * required={"name","preferred_phrase"}
 *
 * Lexicon Class
 */
class Lexicon extends Model implements HasMedia
{
    /**
     * @OA\Property(format="int64", title="ID", default=0, description="ID", property="id"),
     * @OA\Property(format="string", title="preferred_phrase", default="preferred_phrase", description="preferred_phrase", property="preferred_phrase"),
     * @OA\Property(format="string", title="guidance_for_usage", default="guidance_for_usage", description="guidance_for_usage", property="guidance_for_usage"),
     * @OA\Property(format="int64", title="therapy_area_id", default="1", description="therapy_area_id", property="therapy_area_id"),
     */
    use SoftDeletes;

    use InteractsWithMedia;
    use HasFactory;

    public $table = 'lexicons';

    protected $fillable = [
        'therapy_area_id',
        'preferred_phrase',
        'guidance_for_usage',
        'non_preferred_terms',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')->fit('crop', 50, 50);
        $this->addMediaConversion('preview')->fit('crop', 120, 120);
    }

    public function therapy_area(): BelongsTo
    {
        return $this->belongsTo(TherapyArea::class, 'therapy_area_id');
    }

    public function getAllLexicon(): LengthAwarePaginator
    {
        return DB::table('lexicons')
            ->select('id', 'preferred_phrase', 'guidance_for_usage', 'non_preferred_terms', 'order_by')
            ->whereNull('deleted_at')
            ->orderBy('order_by', 'asc')
            ->paginate(200);
    }

    public function getLexiconByTherapyId($id): LengthAwarePaginator
    {
        return DB::table('lexicons')
            ->select('id', 'preferred_phrase', 'guidance_for_usage', 'non_preferred_terms', 'created_at', 'updated_at', 'order_by')
            ->whereNull('deleted_at')
            ->where('therapy_area_id', $id)
            ->orderBy('order_by', 'asc')
            ->paginate(200);
    }

    public function getLexiconById($id): Collection
    {
        return DB::table('lexicons')
            ->select('id', 'preferred_phrase', 'guidance_for_usage', 'non_preferred_terms', 'created_at', 'updated_at', 'order_by')
            ->whereNull('deleted_at')
            ->where('id', $id)
            ->get();
    }

    public function updateLexiconById($id, $lexicons)
    {
        DB::table('lexicons')
            ->where('id', $id)
            ->update($lexicons);
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
