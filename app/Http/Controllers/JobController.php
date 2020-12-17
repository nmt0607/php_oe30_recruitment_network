<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tag;
use App\Models\Job;
use App\Models\User;

class JobController extends Controller
{
    public function index()
    {
        $tag = Auth::user()->tags->where('type', config('tag_config.skill'))->first();
        if (is_null($tag)) {
            $suitableJobs = Job::orderBy('created_at', 'desc')->with('tags')->get();
        } else {
            $suitableJobs = $tag->jobs;
        }
        $skills = Tag::where('type', config('tag_config.skill'))->get();
        $langs = Tag::where('type', config('tag_config.language'))->get();
        $workingTimes = Tag::where('type', config('tag_config.working_time'))->get();


        return view('listjob', [
            'jobs' => Job::orderBy('created_at', 'desc')->with('tags')->get(),
            'suitableJobs' => $suitableJobs,
            'skills' => $skills,
            'langs' => $langs,
            'workingTimes' => $workingTimes,
        ]);
    }

    public function create()
    {
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

    public function store(Request $request)
    {
        $job=Job::create($request->all());
        $job->tags()->attach($request->tag);
    }

    public function show($id)
    {
        $job = Job::findOrFail($id);
        $tag = $job->tags->where('type', config('tag_config.skill'))->first();
        if (is_null($tag)) {
            $similarJobs = Job::orderBy('created_at', 'desc')->with('tags')->get();
        } else {
            $similarJobs = $tag->jobs;
        }

        return view('job_detail', [
            'similarJobs' => $similarJobs,
            'job' => $job,
        ]);
    }

    public function edit($id)
    {
        $job = Job::findOrFail($id);
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

    public function update(Request $request, $id)
    {
        $job = Job::findOrFail($id);
        $job->update($request->all());
        $job->tags()->sync($request->tag);
    }

    public function destroy($id)
    {
        $job = Job::findOrFail($id)->delete();
        $job->tags()->detach();
    }

    public function apply($id)
    {
        $user = Auth::user();
        $job = Job::findOrFail($id);
        $job->users()->attach($user->id, ['status' => config('job_config.waiting')]);
        $skills = Tag::where('type', config('tag_config.skill'))->get();
        $langs = Tag::where('type', config('tag_config.language'))->get();
        $workingTimes = Tag::where('type', config('tag_config.working_time'))->get();

        return view('apply_list', [
            'user' => $user,
            'skills' => $skills,
            'langs' => $langs,
            'workingTimes' => $workingTimes,
        ]);
    }

    public function showApplyList()
    {
        return view('apply_list');
    }

    public function showListCandidateApply($id)
    {
        $job = Job::findOrFail($id);
        $users = $job->users()->orderBy('applications.status','asc')->get();

        return view('candidate', [
            'job' => $job,
            'users' => $users,
        ]);
    }

    public function showHistoryCreateJob()
    {
        $jobs = Auth::user()->company->jobs()->orderBy('created_at', 'desc')->get();

        return view('job_history', [
            'jobs' => $jobs,
        ]);
    }

    public function acceptOrReject($userId, $jobId, $status)
    {
        $job = Job::findOrFail($jobId);
        $job->users()->where('user_id', $userId)->update(['applications.status' => $status]);
        $users = $job->users()->orderBy('applications.status','asc')->get();

        return view('candidate', [
            'job' => $job,
            'users' => $users,
        ]);
    }
}
