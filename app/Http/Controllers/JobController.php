<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Job;
use App\Tag;

class JobController extends Controller
{
    public function index()
    {
        $skills = Tag::where('type', config('tag_config.skill'))->get();
        $langs = Tag::where('type', config('tag_config.language'))->get();
        $workingTimes = Tag::where('type', config('tag_config.working_time'))->get();

        return view('listjob', [
            'jobs' => Job::orderBy('created_at', 'desc')->with('tags')->paginate(5),
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
        $similarJobs = Job::all();
        $job = Job::findOrFail($id);

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
}
