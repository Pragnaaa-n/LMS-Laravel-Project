<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $table = 'roles';

    protected $fillable = [
        'name',
    ];



public function users()
{
  return $this->belongsToMany(User::class, 'user_have_role');
}
public function permissions()
{
 return $this->belongsToMany(Permission::class, 'role_have_permission', 'role_id', 'permission_id');
}
}
