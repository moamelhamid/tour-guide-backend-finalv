<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Department extends Model
{
    protected $fillable = ['name'];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
    public function schedules(): HasMany
    {
        return $this->hasMany(schedule::class);
    }
    public function tournots(): HasMany
    {
        return $this->hasMany(Tournot::class);
    }   
}
