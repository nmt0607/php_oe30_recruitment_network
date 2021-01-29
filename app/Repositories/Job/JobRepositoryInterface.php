<?php

namespace App\Repositories\Job;

use App\Repositories\RepositoryInterface;
use Illuminate\Http\Request;

interface JobRepositoryInterface extends RepositoryInterface
{
    public function searchJob($title);

    public function searchCompany($name);

    public function getJobByTag($id);

    public function getSuitableJob();

    public function getAppliedJob();

    public function getApproveJob();

    public function getAllJobs();

    public function getSkill();

    public function getLang();
}
