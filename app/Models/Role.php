<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $fillable = ['name']; // Allow mass assignment for 'name' if needed

    /**
     * A role has many users.
     */
    public function users()
    {
        return $this->hasMany(User::class);
    }
}
