<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TeamGroup extends Model
{
    protected $fillable = [
        'team_id', 'user_id'
    ];

    /**
     * Get associated user
     * @return object
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
