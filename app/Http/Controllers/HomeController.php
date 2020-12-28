<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Job;
use DB;
use Session;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $allJobs = Job::OrderBy('created_at', 'desc')->get();
        
        $newJobs = $allJobs->take(config('user.limit'))->all();
        if (Auth::check()) {
            $tags = array();
            $tagSkill = Auth::user()->tags->where('type', config('tag_config.skill'))->first();

            if ($tagSkill) {
                array_push($tags, $tagSkill->id);
            }

            $tagLang = Auth::user()->tags->where('type', config('tag_config.language'))->first();

            if ($tagLang) {
                array_push($tags, $tagLang->id);
            }

            if (count($tags)) {
                $suitableJobsId = DB::table('jobs')
                    ->join('taggables', 'jobs.id', '=', 'taggables.taggable_id')
                    ->join('tags', 'tags.id', '=', 'taggables.tag_id')
                    ->select('jobs.id')
                    ->whereIn('tags.id', $tags)
                    ->where('taggable_type', Job::class)
                    ->groupBy('jobs.id')
                    ->havingRaw('count(jobs.id)=' . count($tags))
                    ->get()->pluck('id');

                $suitableJobs = Job::whereIn('id', $suitableJobsId)->orderBy('created_at', 'desc')->get();

                return view('home', compact('allJobs', 'newJobs', 'suitableJobs'));
            }
        }

        return view('home', compact('allJobs', 'newJobs'));
    }

    public function changeLanguage($locale)
    {
        Session::put('language', $locale);

        return redirect()->back();
    }
}
