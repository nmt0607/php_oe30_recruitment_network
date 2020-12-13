<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Job extends Model
{
    protected $fillable = [
        'title',
        'description',
        'experience',
        'salary',
        'company_id',
        'status',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class, 'applications');
    }

    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

}
