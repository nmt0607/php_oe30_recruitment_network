<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tag;
use App\Models\Job;
use App\Models\User;
use App\Models\Company;
use DB;

class JobController extends Controller
{
    public function index()
    {
        $tag = Auth::user()->tags->where('type', config('tag_config.skill'))->first();
        if (is_null($tag)) {
            $suitableJobs = Job::with('images')->orderBy('created_at', 'desc')->with('tags')->get();
        } else {
            $suitableJobs = $tag->jobs;
        }
        $skills = Tag::where('type', config('tag_config.skill'))->get();
        $langs = Tag::where('type', config('tag_config.language'))->get();
        $workingTimes = Tag::where('type', config('tag_config.working_time'))->get();
        $jobs = Job::with('images')->orderBy('created_at', 'desc')->with('tags')->get();
        
        foreach ($jobs as $job) {
            $job->url =  $job->images()->where('type', config('user.avatar'))->first()->url;
        }

        foreach ($suitableJobs as $job) {
            $job->url =  $job->images()->where('type', config('user.avatar'))->first()->url;
        }

        return view('listjob', [
            'jobs' => $jobs,
            'suitableJobs' => $suitableJobs,
            'skills' => $skills,
            'langs' => $langs,
            'workingTimes' => $workingTimes,
        ]);
    }

    public function create()
    {
        if ($this->authorize('create', Job::class)) {
            $skills = Tag::where('type', config('tag_config.skill'))->get();
            $langs = Tag::where('type', config('tag_config.language'))->get();
            $workingTimes = Tag::where('type', config('tag_config.working_time'))->get();
            $companyId = Auth::user()->company->id;

            return view('create_job', [
                'skills' => $skills,
                'langs' => $langs,
                'workingTimes' => $workingTimes,
                'id' => $companyId,
            ]);
        }
    }

    public function store(Request $request)
    {
        if ($this->authorize('create', Job::class)) {
            $job = Job::create($request->all());
            $job->tags()->attach($request->tag);
        }
    }

    public function show($id)
    {
        $job = Job::with('images')->findOrFail($id);
        $job->url = $job->images()->where('type', config('user.avatar'))->first()->url;
        
        $tag = $job->tags->where('type', config('tag_config.skill'))->first();
        if (is_null($tag)) {
            $similarJobs = Job::orderBy('created_at', 'desc')->with('tags')->get();
        } else {
            $similarJobs = $tag->jobs;
        }
        $jobCurrent = Job::where('id', $id)->get();
        $similarJobs = $similarJobs->diff($jobCurrent);

        return view('job_detail', [
            'similarJobs' => $similarJobs,
            'job' => $job,
        ]);
    }

    public function edit($id)
    {
        $job = Job::findOrFail($id);
        if ($this->authorize('update', $job)) {
            $skills = Tag::where('type', config('tag_config.skill'))->get();
            $langs = Tag::where('type', config('tag_config.language'))->get();
            $workingTimes = Tag::where('type', config('tag_config.working_time'))->get();

            return view('edit_job', [
                'job' => $job,
                'skills' => $skills,
                'langs' => $langs,
                'workingTimes' => $workingTimes,
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $job = Job::findOrFail($id);
        if ($this->authorize('update', $job)) {
            $job->update($request->all());
            $job->tags()->sync($request->tag);
        }
    }

    public function destroy($id)
    {
        $job = Job::findOrFail($id);
        if ($this->authorize('update', $job)) {
            $job->tags()->detach();
            $job->delete();
        }
    }

    public function apply($id)
    {
        $user = Auth::user();
        $job = Job::findOrFail($id);
        $job->users()->attach($user->id, ['status' => config('job_config.waiting')]);
        $applyJobs = $user->jobs()->orderBy('applications.status', 'asc')->get();
        $skills = Tag::where('type', config('tag_config.skill'))->get();
        $langs = Tag::where('type', config('tag_config.language'))->get();
        $workingTimes = Tag::where('type', config('tag_config.working_time'))->get();

        return view('apply_list', [
            'jobs' => $applyJobs,
            'skills' => $skills,
            'langs' => $langs,
            'workingTimes' => $workingTimes,
        ]);
    }

    public function cancelApply($id)
    {
        $user = Auth::user();
        $job = Job::findOrFail($id);
        $job->users()->detach($user->id);
        $applyJobs = $user->jobs()->orderBy('applications.status', 'asc')->get();
        $skills = Tag::where('type', config('tag_config.skill'))->get();
        $langs = Tag::where('type', config('tag_config.language'))->get();
        $workingTimes = Tag::where('type', config('tag_config.working_time'))->get();

        return view('apply_list', [
            'jobs' => $applyJobs,
            'skills' => $skills,
            'langs' => $langs,
            'workingTimes' => $workingTimes,
        ]);
    }

    public function showApplyList()
    {
        $applyJobs = Auth::user()->jobs()->orderBy('applications.status', 'asc')->get();

        return view('apply_list', [
            'jobs' => $applyJobs,
        ]);
    }

    public function showListCandidateApply($id)
    {
        $job = Job::findOrFail($id);
        if ($this->authorize('update', $job)) {
            $users = $job->users()->orderBy('applications.status', 'asc')->get();

            return view('candidate', [
                'job' => $job,
                'users' => $users,
            ]);
        }
    }

    public function showHistoryCreateJob()
    {
        if ($this->authorize('create', Job::class)) {
            $jobs = Auth::user()->company->jobs()->orderBy('created_at', 'desc')->get();

            return view('job_history', [
                'jobs' => $jobs,
            ]);
        }
    }

    public function acceptOrReject($userId, $jobId, $status)
    {
        $job = Job::findOrFail($jobId);
        if ($this->authorize('update', $job)) {
            $job->users()->where('user_id', $userId)->update(['applications.status' => $status]);
            $users = $job->users()->orderBy('applications.status', 'asc')->get();

            return view('candidate', [
                'job' => $job,
                'users' => $users,
            ]);
        }
    }

    public function filter(Request $request)
    {
        if (is_null($request->tag)) {
            $jobs = Job::all();

            return view('layouts.filter_job', [
                'jobs' => $jobs,
            ]);
        }
        $filterJobsId = DB::table('jobs')
            ->join('taggables', 'jobs.id', '=', 'taggables.taggable_id')
            ->join('tags', 'tags.id', '=', 'taggables.tag_id')
            ->select('jobs.id')
            ->whereIn('tags.id', $request->tag)
            ->where('taggable_type', Job::class)
            ->groupBy('jobs.id')
            ->havingRaw('count(jobs.id)=' . count($request->tag))
            ->get()->pluck('id');
        $filterJobs = Job::with('images')->whereIn('id', $filterJobsId)->get();

        foreach ($filterJobs as $job) {
            $job->url =  $job->images()->where('type', config('user.avatar'))->first()->url;
        }

        return view('layouts.filter_job', [
            'jobs' => $filterJobs,
        ]);
    }

    public function search(Request $request)
    {
        if ($request->title) {
            $jobs = Job::with('images')->where('title', 'LIKE', '%' . $request->title . '%')->get();

            foreach ($jobs as $job) {
                $job->url =  $job->images()->where('type', config('user.avatar'))->first()->url;
            }

            return view('search_jobs', [
                'jobs' => $jobs,
            ]);
        }

        $companies = Company::with('images')->where('name', 'LIKE', '%' . $request->name . '%')->get();

        foreach ($companies as $company) {
            $company->url =  $company->images()->where('type', config('user.avatar'))->first();
        }

        return view('search_company', [
            'companies' => $companies,
        ]);
    }

    public function findJobByTag($id)
    {
        $tag = Tag::findOrFail($id);
        $jobs = $tag->jobs;
        $tag = Auth::user()->tags->where('type', config('tag_config.skill'))->first();
        $suitableJobs = $tag->jobs;
        if (is_null($tag)) {
            $suitableJobs = Job::orderBy('created_at', 'desc')->with('tags')->get();
        }
        $skills = Tag::where('type', config('tag_config.skill'))->get();
        $langs = Tag::where('type', config('tag_config.language'))->get();
        $workingTimes = Tag::where('type', config('tag_config.working_time'))->get();

        return view('listjob', [
            'jobs' => $jobs,
            'suitableJobs' => $suitableJobs,
            'skills' => $skills,
            'langs' => $langs,
            'workingTimes' => $workingTimes,
        ]);
    }
}
