<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $table = 'roles';

    public const IS_SUPERADMIN = "superAdmin";
    public const IS_ADMIN = "admin";
    public const IS_WAITER = "waiter";

    protected $fillable = [
        'name',
        'role_id',
    ];

    protected function users() {
        return $this->hasMany(User::class);
    }
}
