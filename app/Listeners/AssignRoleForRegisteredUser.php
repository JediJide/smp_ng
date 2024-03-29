<?php

namespace App\Listeners;

use App\Models\Role;
use Illuminate\Auth\Events\Registered;

class AssignRoleForRegisteredUser
{
    protected $defaultRole;

    protected $user;

    /**
     * Create the event listener.
     */
    public function __construct()
    {
        $this->defaultRole = config('project.registered_user_role');
    }

    /**
     * Handle the event.
     */
    public function handle(Registered $event): void
    {
        $this->user = $event->user;

        if (! $this->hasRole($this->defaultRole)) {
            $this->attachRole($this->defaultRole);
        }
    }

    protected function hasRole(string $name)
    {
        return $this->user->roles()->where('title', $name)->exists();
    }

    protected function attachRole(string $name)
    {
        if ($role = Role::where('title', $name)->first()) {
            $this->user->roles()->attach($role);
        }
    }
}
