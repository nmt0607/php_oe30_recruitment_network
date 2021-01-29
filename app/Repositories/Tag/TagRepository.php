<?php

namespace App\Repositories\Tag;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tag;
use App\Models\Job;
use App\Models\User;
use App\Models\Company;
use DB;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class TagRepository extends BaseRepository implements TagRepositoryInterface
{
    public function getModel()
    {
        return Tag::class;
    }
}
