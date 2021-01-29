<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Tag;
use App\Models\Job;
use App\Models\User;
use App\Models\Company;
use DB;
use Alert;
use App\Repositories\Job\JobRepositoryInterface;
use App\Repositories\Tag\TagRepositoryInterface;

class SearchController extends Controller
{
    public function __construct(JobRepositoryInterface $jobRepository, TagRepositoryInterface $tagRepository)
    {
        $this->jobRepository = $jobRepository;
        $this->tagRepository = $tagRepository;
    }

    public function filter(Request $request)
    {
        if (is_null($request->tag)) {
            $jobs = $this->jobRepository->getApproveJob();
            $appliedJobs = $this->jobRepository->getAppliedJob();

            return view('layouts.filter_job', [
                'jobs' => $jobs,
                'appliedJobs' => $appliedJobs,
            ]);
        }
        $filterJobs = $this->jobRepository->getFilterJob($request);
        if (Auth::check()) {
            $appliedJobs = $this->jobRepository->getAppliedJob();

            return view('layouts.filter_job', [
                'appliedJobs' => $appliedJobs,
                'jobs' => $filterJobs,
            ]);
        }

        return view('layouts.filter_job', [
            'jobs' => $filterJobs,
        ]);
    }

    public function search(Request $request)
    {
        if ($request->title) {

            $jobs = $this->jobRepository->searchJob($request->title);

            return view('search_jobs', [
                'jobs' => $jobs,
            ]);
        }

        $companies = $this->jobRepository->searchCompany($request->name);

        return view('search_company', [
            'companies' => $companies,
        ]);
    }

    public function findJobByTag($id)
    {
        $allJobs = $this->jobRepository->getJobByTag($id);
        $suitableJobs = $this->jobRepository->getSuitableJob();
        $appliedJobs = $this->jobRepository->getAppliedJob();
        $tags = $this->tagRepository->getAll();
        $skills = $tags->where('type', config('tag_config.skill'));
        $langs = $tags->where('type', config('tag_config.language'));
        $workingTimes = $tags->where('type', config('tag_config.working_time'));

        return view('listjob', compact('allJobs', 'suitableJobs', 'appliedJobs', 'skills', 'langs', 'workingTimes'));
    }
}
