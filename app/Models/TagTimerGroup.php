<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TagTimerGroup extends Model
{
    protected $fillable = [
        'timer_id', 'tag_id'
    ];

    /**
     * Get the related tag
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function tag()
    {
        return $this->belongsTo(Tag::class);
    }

    /**
     * Get the related timer
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function timer()
    {
        return $this->belongsTo(Timer::class);
    }
}
