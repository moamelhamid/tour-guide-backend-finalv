<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Schedule extends Model
{
    protected $fillable = [
        'title',
        'dep_id',
        'link_image',


    ];

    public function department() : BelongsTo
    {
        return $this->belongsTo(department::class, 'dep_id'); 
    }

}
