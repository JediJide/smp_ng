<?php

namespace App\Models;

use DateTimeInterface;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class StatementStatus extends Model
{
    use SoftDeletes;
    use HasFactory;

    public $table = 'statement_statuses';

    protected $fillable = [
        'status',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function statusStatements()
    {
        return $this->hasMany(Statement::class, 'status_id', 'id');
    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}