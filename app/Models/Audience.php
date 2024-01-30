<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @OA\Schema(),
 * required={"name"}
 *
 * Audience Class
 */
class Audience extends Model
{
    use HasFactory;

    /**
     * @OA\Property(format="string", title="name", default="Audience Name", description="name", property="name"),
     */
    use SoftDeletes;

    public $table = 'audience';

    protected $fillable = [
        'name',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    protected $hidden = ['pivot'];

    public function audienceStatements(): BelongsToMany
    {
        return $this->belongsToMany(Statement::class);
    }

    protected function serializeDate(DateTimeInterface $date): string
    {
        return $date->format('Y-m-d H:i:s');
    }
}
