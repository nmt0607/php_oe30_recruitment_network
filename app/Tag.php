<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    public function jobs()
    {
        return $this->morphedByMany(Job::class, 'taggable');
    }

    /**
     * Get all of the videos that are assigned this tag.
     */
    public function users()
    {
        return $this->morphedByMany(User::class, 'taggable');
    }
}
