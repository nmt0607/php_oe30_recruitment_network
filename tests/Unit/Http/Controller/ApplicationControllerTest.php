<?php

namespace Tests\Unit\Http\Controller;

use Tests\TestCase;
use Mockery;
use App\Models\User;
use App\Models\Company;
use App\Models\Job;
use App\Http\Controllers\ApplicationController;
use App\Repositories\Application\ApplicationRepositoryInterface;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ApplicationControllerTest extends TestCase
{
    protected $applicationMock, $applicationController;

    public function setUp(): void
    {
        parent::setUp();
        $this->applicationMock = Mockery::mock(ApplicationRepositoryInterface::class)->makePartial();
        $this->applicationController = new ApplicationController($this->applicationMock);
    }

    public function tearDown(): void
    {
        Mockery::close();
        unset($this->applicationController);
        unset($this->applicationMock);
        parent::tearDown();
    }

    public function test_apply()
    {
        $user = factory(User::class)->make();
        $user->id = 1;
        $this->be($user);
        $job = factory(Job::class)->make();
        $job->id = 5;
        $this->applicationMock->shouldReceive('apply')->with($job->id, $user->id);
        $controller = $this->applicationController->apply($job->id);
        $this->assertEquals(redirect()->route('show_apply_list'), $controller);
    }

    public function test_apply_not_found_job()
    {
        $user = factory(User::class)->make();
        $user->id = 1;
        $this->be($user);
        $this->expectException(ModelNotFoundException::class);
        $this->applicationMock->shouldReceive('apply')->with(30, $user->id)->andThrow(ModelNotFoundException::class);
        $controller = $this->applicationController->apply(30);
    }

    public function test_cancelApply()
    {
        $user = factory(User::class)->make();
        $user->id = 1;
        $this->be($user);
        $job = factory(Job::class)->make();
        $job->id = 5;
        $this->applicationMock->shouldReceive('cancelApply')->with($job->id, $user->id);
        $controller = $this->applicationController->cancelApply($job->id);
        $this->assertEquals(redirect()->route('show_apply_list'), $controller);
    }

    public function test_cancelApply_not_found_job()
    {
        $user = factory(User::class)->make();
        $user->id = 1;
        $this->be($user);
        $this->expectException(ModelNotFoundException::class);
        $this->applicationMock->shouldReceive('cancelApply')->with(30, $user->id)->andThrow(ModelNotFoundException::class);
        $controller = $this->applicationController->cancelApply(30);
    }

    public function test_showApplyList()
    {
        $user = factory(User::class)->make();
        $user->id = 1;
        $this->be($user);
        $jobs = factory(Job::class, 5)->make();
        $this->applicationMock->shouldReceive('applyJobs')->with($user)->andReturn($jobs);
        $controller = $this->applicationController->showApplyList();
        $this->assertEquals('apply_list', $controller->getName());
        $this->assertArrayHasKey('jobs', $controller->getData());
    }

    public function test_showListCandidateApply_unAuthorized()
    {
        $user = factory(User::class)->make();
        $listCandidate = factory(User::class, 5)->make();
        $companyOfUser = factory(Company::class)->make();
        $companyOfJob = factory(Company::class)->make();
        $user->id = 1;
        $user->role_id = 2;
        $this->be($user);
        $companyOfUser->id = 3;
        $companyOfJob->id = 4;
        $job = factory(Job::class)->make();
        $job->id = 5;
        $user->setRelation('company', $companyOfUser);
        $job->setRelation('company', $companyOfJob);
        $this->expectException(AuthorizationException::class);
        $this->applicationMock->shouldReceive('getJob')->with($job->id)->andReturn($job);
        $this->applicationMock->shouldReceive('showListCandidateApply')->with($job)->andReturn($listCandidate);
        $controller = $this->applicationController->showListCandidateApply($job->id);
        $this->assertEquals('candidate', $controller->getName());
        $this->assertArrayHasKey('job', $controller->getData());
        $this->assertArrayHasKey('users', $controller->getData());
    }

    public function test_showListCandidateApply_authorized()
    {
        $user = factory(User::class)->make();
        $listCandidate = factory(User::class, 5)->make();
        $company = factory(Company::class)->make();
        $user->id = 1;
        $user->role_id = 2;
        $company->id = 3;
        $this->be($user);
        $job = factory(Job::class)->make();
        $job->id = 3;
        $user->setRelation('company', $company);
        $job->setRelation('company', $company);
        $this->applicationMock->shouldReceive('getJob')->with($job->id)->andReturn($job);
        $this->applicationMock->shouldReceive('showListCandidateApply')->with($job)->andReturn($listCandidate);
        $controller = $this->applicationController->showListCandidateApply($job->id);
        $this->assertEquals('candidate', $controller->getName());
        $this->assertArrayHasKey('job', $controller->getData());
        $this->assertArrayHasKey('users', $controller->getData());
    }

    public function test_showListCandidateApply_not_found_job()
    {
        $user = factory(User::class)->make();
        $user->id = 1;
        $user->role_id = 2;
        $this->be($user);
        $this->expectException(ModelNotFoundException::class);
        $this->applicationMock->shouldReceive('getJob')->with(10)->andThrow(ModelNotFoundException::class);
        $this->applicationMock->shouldReceive('showListCandidateApply')->with(10);
        $controller = $this->applicationController->showListCandidateApply(10);
        $this->assertEquals('candidate', $controller->getName());
        $this->assertArrayHasKey('job', $controller->getData());
        $this->assertArrayHasKey('users', $controller->getData());
    }


    public function test_showHistoryCreateJob_authorized()
    {
        $user = factory(User::class)->make();
        $user->id = 1;
        $user->role_id = 2;
        $this->be($user);
        $jobs = factory(Job::class, 10)->make();
        $this->applicationMock->shouldReceive('showHistoryCreateJob')->with($user)->andReturn($jobs);
        $controller = $this->applicationController->showHistoryCreateJob();
        $this->assertEquals('job_history', $controller->getName());
        $this->assertArrayHasKey('jobs', $controller->getData());
    }

    public function test_showHistoryCreateJob_unAuthorized()
    {
        $user = factory(User::class)->make();
        $user->id = 1;
        $user->role_id = 1;
        $this->be($user);
        $jobs = factory(Job::class, 10)->make();
        $this->expectException(AuthorizationException::class);
        $this->applicationMock->shouldReceive('showHistoryCreateJob')->with($user)->andReturn($jobs);
        $controller = $this->applicationController->showHistoryCreateJob();
        $this->assertEquals('job_history', $controller->getName());
        $this->assertArrayHasKey('jobs', $controller->getData());
    }

    public function test_acceptOrReject_authorized()
    {
        $user = factory(User::class)->make();
        $company = factory(Company::class)->make();
        $user->id = 1;
        $user->role_id = 2;
        $this->be($user);
        $job = factory(Job::class)->make();
        $job->id = 2;
        $user->setRelation('company', $company);
        $job->setRelation('company', $company);
        $this->applicationMock->shouldReceive('find')->with($job->id)->andReturn($job);
        $this->applicationMock->shouldReceive('acceptOrReject')->with($job, $user->id, 2);
        $controller = $this->applicationController->acceptOrReject($user->id, $job->id, 2);
        $this->assertEquals(route('list_candidate', ['id' => $job->id]), $controller->getTargetUrl());
    }

    public function test_acceptOrReject_unAuthorized()
    {
        $user = factory(User::class)->make();
        $companyOfUser = factory(Company::class)->make();
        $companyOfJob = factory(Company::class)->make();
        $user->id = 1;
        $companyOfUser->id = 2;
        $companyOfJob->id = 3;
        $user->role_id = 2;
        $this->be($user);
        $job = factory(Job::class)->make();
        $job->id = 2;
        $user->setRelation('company', $companyOfUser);
        $job->setRelation('company', $companyOfJob);
        $this->expectException(AuthorizationException::class);
        $this->applicationMock->shouldReceive('find')->with($job->id)->andReturn($job);
        $this->applicationMock->shouldReceive('acceptOrReject')->with($job, $user->id, 2);
        $controller = $this->applicationController->acceptOrReject($user->id, $job->id, 2);
        $this->assertEquals(route('list_candidate', ['id' => $job->id]), $controller->getTargetUrl());
    }


    public function test_acceptOrReject_not_found_job()
    {
        $user = factory(User::class)->make();
        $user->id = 2;
        $user->role_id = 2;
        $this->be($user);
        $this->expectException(ModelNotFoundException::class);
        $this->applicationMock->shouldReceive('find')->with(10)->andThrow(ModelNotFoundException::class);
        $this->applicationMock->shouldReceive('acceptOrReject')->with(10, $user->id, 2);
        $controller = $this->applicationController->acceptOrReject($user->id, 10, 2);
    }
}
