<?php

namespace App\Repositories\Job;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tag;
use App\Models\Job;
use App\Models\User;
use App\Models\Company;
use DB;
use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;

class JobRepository extends BaseRepository implements JobRepositoryInterface
{
    public function getModel()
    {
        return Job::class;
    }

    public function searchJob($title)
    {
        $jobs = Job::where('status', config('job_config.approve'))->with('images')->where('title', 'LIKE', '%' . $title . '%')->get();
        foreach ($jobs as $job) {
            $job->url = $job->images()->where('type', config('user.avatar'))->first()->url;
        }

        return $jobs;
    }

    public function searchCompany($name)
    {
        $companies = Company::with('images')->where('name', 'LIKE', '%' . $name . '%')->get();
        foreach ($companies as $company) {
            $company->url =  $company->images()->where('type', config('user.avatar'))->first()->url;
        }

        return $companies;
    }

    public function getJobByTag($id)
    {
        $tag = Tag::findOrFail($id);
        $jobs = $tag->jobs()->where('status', config('job_config.approve'))->paginate(config('job_config.paginate'));

        return $jobs;
    }

    public function getSuitableJob()
    {
        $tag = Auth::user()->tags->where('type', config('tag_config.skill'))->first();
        $suitableJobs = $tag->jobs()->where('status', config('job_config.approve'))->get();
        if (is_null($tag)) {
            $suitableJobs = Job::orderBy('created_at', 'desc')->with('tags')->get();
        }
        foreach ($suitableJobs as $job) {
            $job->url =  $job->images()->where('type', config('user.avatar'))->first()->url;
        }

        return $suitableJobs;
    }

    public function getAppliedJob()
    {
        $appliedJobs = null;
        if (Auth::check()) {
            $appliedJobs = Auth::user()->jobs()->where('applications.status', config('job_config.waiting'))->get();
        }

        return $appliedJobs;
    }

    public function getApproveJob()
    {
        $jobs = Job::where('status', config('job_config.approve'))->get();
        foreach ($jobs as $job) {
            $job->url =  $job->images()->where('type', config('user.avatar'))->first()->url;
        }

        return $jobs;
    }

    public function getFilterJob($request)
    {
        $filterJobsId = DB::table('jobs')
            ->join('taggables', 'jobs.id', '=', 'taggables.taggable_id')
            ->join('tags', 'tags.id', '=', 'taggables.tag_id')
            ->select('jobs.id')
            ->where('status', config('job_config.approve'))
            ->whereIn('tags.id', $request->tag)
            ->where('taggable_type', Job::class)
            ->groupBy('jobs.id')
            ->havingRaw('count(jobs.id)=' . count($request->tag))
            ->get()->pluck('id');
        $filterJobs = Job::with('images')->whereIn('id', $filterJobsId)->get();

        foreach ($filterJobs as $job) {
            $job->url =  $job->images()->where('type', config('user.avatar'))->first()->url;
        }

        return $filterJobs;
    }

    public function getAllJobs()
    {
        $allJobs = Job::where('status', config('job_config.approve'))->with('images')->orderBy('created_at', 'desc')->paginate(config('job_config.paginate'));
        foreach ($allJobs as $job) {
            $job->url =  $job->images()->where('type', config('user.avatar'))->first()->url;
        }
        return $allJobs;
    }

    public function getSkill()
    {
        $tagSkill = Auth::user()->tags->where('type', config('tag_config.skill'))->first();

        return $tagSkill;
    }

    public function getLang()
    {
        $tagLang = Auth::user()->tags->where('type', config('tag_config.lang'))->first();

        return $tagLang;
    }
}
