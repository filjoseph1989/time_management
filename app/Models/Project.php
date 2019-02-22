<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    protected $fillable = [
        'name', 'user_id', 'created_by', 'color',
        'status', 'project_status'
    ];

    /**
     * Get associated timers
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasMany
     */
    public function timers()
    {
        return $this->hasMany(Timer::class);
    }

    /**
     * Get associated team
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function team()
    {
        return $this->hasOne(Team::class);
    }

    /**
     * Get associated team
     *
     * @return \Illuminate\Database\Eloquent\Relations\hasOne
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get my projects
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builde
     */
    public function scopeMine($query)
    {
        return $query->whereUserId(auth()->user()->id);
    }
}
