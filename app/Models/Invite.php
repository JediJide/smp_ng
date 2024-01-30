<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @OA\Schema(),
 * required={"email","role"}
 *
 * Invite Class
 */
class Invite extends Model
{
    // protected $connection = 'common_database';
    /**
     * @OA\Property(format="string", default="email@domain.com", description="email", property="email"),
     *@OA\Property(format="int64", default="2", description="role", property="role"),
     */
    protected $fillable = [
        'email', 'token', 'role', 'password_changed_at',

    ];
    //use HasFactory;
}
