<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Job;
use App\Tag;
use App\User;
use App\Company;
use DB;


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

        return view('create_job', [
            'skills' => $skills,
            'langs' => $langs,
            'workingTimes' => $workingTimes,
            'id' => 2,
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
        $tagID = $job->tags->first()->id;

        $tag = Tag::findOrFail($tagID);
        $tag->load('jobs');
        $similarJobs=$tag->jobs;


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

    public function userApply($id)
    {
        $user = User::findOrFail(1);
        $job = Job::findOrFail($id);
        $job->users()->attach($user->id, ['status' => 0]);
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
        $user = User::findOrFail(1);

        return view('apply_list', [
            'user' => $user,
        ]);
    }

    public function showListCandidateApply($id)
    {
        $job = Job::findOrFail($id);

        return view('candidate', [
            'job' => $job,
        ]);
    }

    public function showHistoryCreateJob()
    {
        $user = User::findOrFail(1);
        $jobs = $user->company->jobs()->orderBy('created_at', 'desc')->get();

        return view('job_history', [
            'jobs' => $jobs,
        ]);
    }

    public function accept($userId, $jobId)
    {
        $job = Job::findOrFail($jobId);
        $job_user = DB::table('applications')->where([
            ['user_id', '=', $userId],
            ['job_id', '=', $jobId],
        ])->update(['status' => 1]);;

        return view('candidate', [
            'job' => $job,
        ]);
    }

    public function reject($userId, $jobId)
    {
        $job = Job::findOrFail($jobId);
        $job_user = DB::table('applications')->where([
            ['user_id', '=', $userId],
            ['job_id', '=', $jobId],
        ])->update(['status' => 2]);

        return view('candidate', [
            'job' => $job,
        ]);
    }

    public function viewListUser()
    {
        $candidates = User::all()->where('role_id',1);
        $employers = User::all()->where('role_id',2);

        return view('user_list', [
            'candidates' => $candidates,
            'employers' => $employers,
        ]);
    }

    public function viewListJob()
    {
        $approveJobs = Job::all()->where('status',1);
        $unapproveJobs = Job::all()->where('status',0);
        $skills = Tag::where('type', config('tag_config.skill'))->get();
        $langs = Tag::where('type', config('tag_config.language'))->get();
        $workingTimes = Tag::where('type', config('tag_config.working_time'))->get();

        return view('job_list', [
            'approveJobs' => $approveJobs,
            'unapproveJobs' => $unapproveJobs,
            'skills' => $skills,
            'langs' => $langs,
            'workingTimes' => $workingTimes,
        ]);
    }

    public function approveJob($id)
    {
        Job::where('id', $id)->update(['status' => 1]);
        $approveJobs = Job::all()->where('status',1);
        $unapproveJobs = Job::all()->where('status',0);
        $skills = Tag::where('type', config('tag_config.skill'))->get();
        $langs = Tag::where('type', config('tag_config.language'))->get();
        $workingTimes = Tag::where('type', config('tag_config.working_time'))->get();

        return view('job_list', [
            'approveJobs' => $approveJobs,
            'unapproveJobs' => $unapproveJobs,
            'skills' => $skills,
            'langs' => $langs,
            'workingTimes' => $workingTimes,
        ]);
    }
}
