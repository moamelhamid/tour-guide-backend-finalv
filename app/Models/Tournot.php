<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Tournot extends Model
{
    protected $fillable = [ 
    'user_id',
    'title',
    'message',
    'link',
    'is_read',
    'dep_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
    public function department(): BelongsTo
    {
        return $this->belongsTo(department::class, 'dep_id');
    }
}
