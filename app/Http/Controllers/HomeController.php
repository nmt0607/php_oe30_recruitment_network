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
use Session;

class HomeController extends Controller
{
    public function __construct(JobRepositoryInterface $jobRepository)
    {
        $this->jobRepository = $jobRepository;
    }

    public function index()
    {
        $allJobs = $this->jobRepository->getAllJobs();

        $newJobs = $allJobs->take(config('user.limit'))->all();
        if (Auth::check()) {
            $appliedJobs = $this->jobRepository->getAppliedJob();
            $tags = array();
            $tagSkill = $this->jobRepository->getSkill();

            if ($tagSkill) {
                array_push($tags, $tagSkill->id);
            }

            $tagLang = $this->jobRepository->getLang();

            if ($tagLang) {
                array_push($tags, $tagLang->id);
            }

            if (count($tags)) {
                $suitableJobs = $this->jobRepository->getSuitableJob();

                return view('home', compact('allJobs', 'newJobs', 'suitableJobs', 'appliedJobs'));
            }

            return view('home', compact('allJobs', 'newJobs', 'appliedJobs'));
        }

        return view('home', compact('allJobs', 'newJobs'));
    }

    public function changeLanguage($locale)
    {
        Session::put('language', $locale);

        return redirect()->back();
    }
}
