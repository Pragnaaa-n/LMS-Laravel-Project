<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Tymon\JWTAuth\Contracts\JWTSubject;





class User extends Authenticatable implements JWTSubject
{


        protected $table = 'users'; // Ensure this matches your table name

    use HasApiTokens, HasFactory, Notifiable;

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }
    public function getJWTCustomClaims()
    {
        return ['role' => 'user'];
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'mobile_number',
        'profile_photo',
        'role_id',

    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];


    public function role()
    {
        return $this->belongsTo(Role::class);
    }


    // public function roles()
    // {
    //     return $this->belongsToMany(Role::class, 'user_have_role');
    // }

    // // Check if user has a specific role
    // public function hasRole($role)
    // {
    //     return $this->roles->contains('name', $role);
    // }


    // public function roles()
    // {
    //     return $this->belongsToMany(Role::class, 'user_have_role');
    // }

    // public function permissions()
    // {
    //     return $this->belongsToMany(Permission::class, 'role_have_permission', 'role_id', 'permission_id');
    // }

    // // Check if the user has a role
    // public function hasRole($roleName)
    // {
    //     return $this->roles()->where('name', $roleName)->exists();
    // }

    // // Check if the user has a permission
    // public function hasPermission($permissionName)
    // {
    //     return $this->permissions()->where('name', $permissionName)->exists();
    // }



    //  // Many-to-many relationship with roles via the user_have_role pivot table
    //  public function roles()
    //  {
    //      return $this->belongsToMany(Role::class, 'user_have_role');
    //  }

    //  // Many-to-many relationship with permissions via the role_have_permission pivot table
    //  public function permissions()
    //  {
    //      return $this->belongsToMany(Permission::class, 'role_have_permission', 'role_id', 'permission_id');
    //  }

    //  // Check if the user has a specific role
    //  public function hasRole($roleName)
    //  {
    //      return $this->roles()->where('name', $roleName)->exists();
    //  }

    //  // Check if the user has a specific permission
    //  public function hasPermission($permissionName)
    //  {
    //      return $this->permissions()->where('name', $permissionName)->exists();
    //  }


  
}
