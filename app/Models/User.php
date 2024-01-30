<?php

namespace App\Models;

use App\Http\Controllers\Auth\ResetPasswordController;
use App\Notifications\ResetPasswordNotification;
use Carbon\Carbon;
use DateTimeInterface;
use Illuminate\Auth\Notifications\ResetPassword;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Laravel\Sanctum\HasApiTokens;

/**
 * @property mixed $id
 * @OA\Schema(),
 * required={"email", "password"}
 *
 * User Class
 */
class User extends Authenticatable
{
    /**
     * @OA\Property(format="string", default="email@user.com", description="email", property="email"),
     * @OA\Property(format="string", default="password", description="password", property="password"),
     */
    use SoftDeletes;

    use Notifiable;
    use HasFactory;
    use HasApiTokens;

    #shared user database
     public $table = 'users';

    protected $hidden = [
        'remember_token',
        'password',
        'pivot',
    ];

    //protected $hidden = ['pivot'];
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $fillable = [
        'name',
        'last_name',
        'email',
        'email_verified_at',
        'password',
        'remember_token',
        'password_changed_at',
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function getIsAdminAttribute(): bool
    {
        return $this->roles()->where('id', 1)->exists();
    }

    public function userResources(): HasMany
    {
        return $this->hasMany(Resource::class, 'user_id', 'id');
    }

    public function getEmailVerifiedAtAttribute($value): ?string
    {
        return $value ? Carbon::createFromFormat('Y-m-d H:i:s', $value)->format(config('panel.date_format').' '.config('panel.time_format')) : null;
    }

    public function setEmailVerifiedAtAttribute($value)
    {
        $this->attributes['email_verified_at'] = $value ? Carbon::createFromFormat(config('panel.date_format').' '.config('panel.time_format'), $value)->format('Y-m-d H:i:s') : null;
    }

    public function setPasswordAttribute($input)
    {
        if ($input) {
            $this->attributes['password'] = app('hash')->needsRehash($input) ? Hash::make($input) : $input;
        }
    }

    public function sendPasswordResetNotification($token)
    {
        $notification = new ResetPasswordNotification($token);

        // Then you pass the notification
        $this->notify($notification);
    }

    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function getUserByEmail(string $email): Model|\Illuminate\Database\Query\Builder|null
    {
        return  DB::table('users')
            ->whereNotNull('users.deleted_at')
            ->select('users.id','users.name',
                'users.last_name', 'users.email', 'roles.title as role', 'users.created_at', 'users.updated_at', 'users.deleted_at')
            ->join('role_user', 'role_user.user_id', '=', 'users.id')
            ->join('roles', 'roles.id', '=', 'role_user.role_id')
            ->where('users.email', '=', $email)
            ->first();
    }

    public function sendUsersMassEmail(): Collection
    {
        return DB::table('users')
            ->whereNotNull('users.deleted_at')
            ->whereNotNull('password_changed_at')
            ->where ('id','=',1)//,139,135
            ->get ();
    }

//    public function latestLogin(): Collection
//    {
//        return DB::table ('users')
//        ->whereNull ('deleted_at')
//        ->orderBy ('updated_at')
//        ->get ();
//    }

    protected function serializeDate(DateTimeInterface $date)
    {
        return $date->format('Y-m-d H:i:s');
    }
}
