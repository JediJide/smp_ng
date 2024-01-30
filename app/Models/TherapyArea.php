<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @OA\Schema(),
 * required={"name"}
 *
 * Statement Class
 * @method static create(array $validated)
 */
class TherapyArea extends Model
{
    use SoftDeletes;
    use HasFactory;

    public $table = 'therapy_areas';

    protected $fillable = [
        'name',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function therapyAreaStatements()
    {
        return $this->hasMany(Statement::class, 'therapy_area_id', 'id');
    }

    public function therapyAreaGlossaries()
    {
        return $this->hasMany(Glossary::class, 'therapy_area_id', 'id');
    }

    public function therapyAreaLexicons()
    {
        return $this->hasMany(Lexicon::class, 'therapy_area_id', 'id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
