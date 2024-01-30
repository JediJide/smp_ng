<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

/**
 * @OA\Schema(),
 * required={"title"}
 *
 * Reference Class
 * @method static where(string $string, mixed $referenceID)
 * @method static create(array $array)
 */
class Reference extends Model implements HasMedia
{
    /**
     * @OA\Property(format="string", title="title", default="The file title", description="title", property="title"),
     * @OA\Property(format="string", title="title", default="https://www.google.com", description="url", property="url"),
     * @OA\Property(format="binary", type="string", title="file_name", default="", description="File to upload", property="file_name"),
     */
    use SoftDeletes;

    use InteractsWithMedia;
    use HasFactory;

    public $table = 'references';

    protected $appends = [
        'file',
    ];

    protected $fillable = [
        'user_id',
        'temporary_url',
        'file_name',
        'url',
        'tag',
        'title',
        'ip_address',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $hidden = ['pivot'];

    public function registerMediaConversions(Media $media = null): void
    {
        $this->addMediaConversion('thumb')->fit('crop', 50, 50);
        $this->addMediaConversion('preview')->fit('crop', 120, 120);
    }

    public function referenceThemes()
    {
        return $this->belongsToMany(Theme::class);
    }

    public function referenceStatements()
    {
        return $this->belongsToMany(Statement::class);
    }

    public function getFileAttribute()
    {
        return $this->getMedia('file');
    }

    public function getFileNames(): \Illuminate\Support\Collection
    {
        return DB::table('references')
            ->whereRaw('((file_name is not null) or (url is not null))')
            ->whereNull('deleted_at')
            ->select('id', 'title', 'url', 'temporary_url as aws_file', 'file_name')
            ->get();
    }

    public function store($reference): bool
    {
        return  DB::table('references')->insert($reference);
    }

    public function getFileById($id): \Illuminate\Support\Collection
    {
        return DB::table('references')
            ->where('id', '=', $id)
            ->select('id', 'title', 'file_name', 'url', 'temporary_url', 'user_id')
            ->limit(1)
            ->get();
    }

    public function updateReferenceTempUrls($file, $new_url): int
    {
        return DB::table('references')
            ->where('file_name', $file)
            ->update(['temporary_url' => $new_url, 'updated_at' =>  Carbon::now()]);
    }

    public function updateTempReferenceUrlsById($id, $file_name, $new_url): int
    {
        return DB::table('references')
            ->where('id', $id)
            ->update([
                'temporary_url' => $new_url,
                'url' => $new_url,
                'updated_at' =>  Carbon::now(),
                'file_name' => $file_name,
            ]);
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
