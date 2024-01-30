<?php

namespace App\Models;

use App\Support\HasAdvancedFilter;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * @OA\Schema(),
 * required={"title","file_name"}
 *
 * Resource Class
 *
 * @method static where(string $string, $id)
 */
class Resource extends Model implements HasMedia
{
    /**
     * @OA\Property(format="string", title="title", default="The file title", description="title", property="title"),
     * @OA\Property(format="binary", type="string", title="file_name", default="", description="File to upload", property="file_name"),
     */
    use HasAdvancedFilter;

    use HasFactory;
    use InteractsWithMedia;
    use SoftDeletes;

    public $table = 'resources';

    protected $appends = [
        'filename',
    ];

    protected $fillable = [
        'user_id',
        'temporary_url',
        'url',
        'tag',
        'title',
        'ip_address',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $hidden = ['pivot'];

    public function registerMediaConversions(?Media $media = null): void
    {
        $this->addMediaConversion('thumb')->fit('crop', 50, 50);
        $this->addMediaConversion('preview')->fit('crop', 120, 120);
    }

    public function resourceThemes(): BelongsToMany
    {
        return $this->belongsToMany(Theme::class);
    }

    public function resourceStatements(): BelongsToMany
    {
        return $this->belongsToMany(Statement::class);
    }

    public function getFilenameAttribute(): Collection
    {
        return $this->getMedia('filename');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function store($resource): int
    {
        return DB::table('resources')->insertGetId($resource);
    }

    public function getFileNames(): Collection
    {
        return DB::table('resources')
            ->whereNotNull('file_name')
            ->whereNull('deleted_at')
            ->select('id', 'title', 'url', 'user_id', 'file_name', 'is_header_resource')
            ->get();
    }

    public function getFileById($id): Collection
    {
        return DB::table('resources')
            ->where('id', '=', $id)
            ->select('id', 'title', 'file_name', 'url', 'file_mime_type', 'file_size', 'temporary_url', 'user_id')
            ->limit(1)
            ->get();
    }

    public function updateResourceTempUrls($file, $new_url): int
    {
        return DB::table('resources')
            ->where('file_name', $file)
            ->update(['temporary_url' => $new_url, 'updated_at' => Carbon::now()]);
    }

    public function updateTempResourceUrlsById($id, $resource): int
    {
        return DB::table('resources')
            ->where('id', $id)
            ->update($resource);
    }

    public function updateResourceLinkField($id, $is_linked)
    {
        return Resource::where('id', $id)
            ->update(['is_linked' => $is_linked]);
    }

    public function delete_unlinked_Resources()
    {
        $file_names = DB::table('resources')
            ->select('resources.id', 'resources.file_name')
            ->whereNull('is_linked')
            ->get();

        $this->extracted($file_names);

    }

    public function extracted(Collection $file_names): void
    {
        $aws_path = config('app.aws_path');

        if (! $file_names->isEmpty()) {
            foreach ($file_names as $file_name) {
                $path = $aws_path.'/documents/resources/'.$file_name->file_name;

                if (Storage::disk('s3')->exists($path)) {
                    Storage::disk('s3')->delete($path);
                }
                Resource::where('id', $file_name->id)->forceDelete();

            }

        }
    }

    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }
}
