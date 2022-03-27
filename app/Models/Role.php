<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Role extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'roles';
    protected $guarded = [];

    const IS_ADMIN = 1;
    const IS_USER = 2;

    public function users(){
        return $this->hasMany(User::class);
    }
}
