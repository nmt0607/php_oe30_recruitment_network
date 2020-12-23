<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tag;
use App\Models\Job;
use App\Models\User;

class AdminController extends Controller
{
    public function viewListUser()
    {
        $allUsers = User::all();
        $candidates = $allUsers->where('role_id', config('user.candidate'));
        $employers = $allUsers->where('role_id', config('user.employer'));

        return view('user_list', [
            'candidates' => $candidates,
            'employers' => $employers,
        ]);
    }

    public function blockUser($id)
    {
        $user = User::findOrFail($id);
        $user->update(['status' => config('user.block')]);
        $allUsers = User::all();
        $candidates = $allUsers->where('role_id', config('user.candidate'));
        $employers = $allUsers->where('role_id', config('user.candidate'));

        return view('user_list', [
            'candidates' => $candidates,
            'employers' => $employers,
        ]);
    }

    public function acceptEmployer($id)
    {
        $user = User::findOrFail($id);
        $user->update(['status' => config('user.confirmed')]);
        $allUsers = User::all();
        $candidates = $allUsers->where('role_id', config('user.candidate'));
        $employers = $allUsers->where('role_id', config('user.employer'));

        return view('user_list', [
            'candidates' => $candidates,
            'employers' => $employers,
        ]);
    }

    public function viewListJob()
    {
        $approveJobs = Job::with(['tags', 'company'])->where('status', config('job_config.approve'))->get();
        $unapproveJobs = Job::with(['tags', 'company'])->where('status', config('job_config.unapprove'))->get();

        return view('job_list', [
            'approveJobs' => $approveJobs,
            'unapproveJobs' => $unapproveJobs,
        ]);
    }

    public function approveJob($id)
    {
        Job::where('id', $id)->update(['status' => config('job_config.approve')]);
        $approveJobs = Job::with(['tags', 'company'])->where('status', config('job_config.approve'));
        $unapproveJobs = Job::with(['tags', 'company'])->where('status', config('job_config.unapprove'));

        return view('job_list', [
            'approveJobs' => $approveJobs,
            'unapproveJobs' => $unapproveJobs,
        ]);
    }
}
