<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Job;
use App\Models\Tag;
use App\Models\Company;
use App\Models\User;
use Mockery;
use App\Http\Controllers\SearchController;
use App\Repositories\Job\JobRepositoryInterface;
use App\Repositories\Tag\TagRepositoryInterface;
use Illuminate\Http\Request;

class SearchControllerTest extends TestCase
{
    public function setUp() : void
    {
        parent::setUp();
        $this->jobMock = Mockery::mock(JobRepositoryInterface::class)->makePartial();
        $this->tagMock = Mockery::mock(TagRepositoryInterface::class)->makePartial();
        $this->searchController = new SearchController($this->jobMock, $this->tagMock);
    }

    public function tearDown() : void
    {
        Mockery::close();
        unset($this->searchController);
        parent::tearDown();
    }

    public function test_findJobByTag()
    {
        $jobs = factory(Job::class, 10)->make();
        $tags = factory(Tag::class, 10)->make();
        $this->jobMock->shouldReceive('getJobByTag')->with($tags->first()->id)->andReturn($jobs);
        $this->jobMock->shouldReceive('getSuitableJob')->andReturn($jobs);
        $this->jobMock->shouldReceive('getAppliedJob')->andReturn($jobs);
        $this->tagMock->shouldReceive('getAll')->andReturn($jobs);
        $result = $this->searchController->findJobByTag($tags->first()->id);
        $this->assertEquals('listjob', $result->getName());
        $this->assertArrayHasKey('allJobs', $result->getData());
        $this->assertArrayHasKey('suitableJobs', $result->getData());
        $this->assertArrayHasKey('appliedJobs', $result->getData());
        $this->assertArrayHasKey('skills', $result->getData());
        $this->assertArrayHasKey('langs', $result->getData());
        $this->assertArrayHasKey('workingTimes', $result->getData());
    }

    public function test_search_job()
    {
        $jobs = factory(Job::class, 10)->make();
        $request = new Request();
        $request->title = 'sun';
        $this->jobMock->shouldReceive('searchJob')->with($request->title)->andReturn($jobs);
        $result = $this->searchController->search($request);
        $this->assertEquals('search_jobs', $result->getName());
        $this->assertArrayHasKey('jobs', $result->getData());
    }

    public function test_search_company()
    {
        $companies = factory(Company::class, 10)->make();
        $request = new Request();
        $request->name = 'tuan';
        $this->jobMock->shouldReceive('searchCompany')->with($request->name)->andReturn($companies);
        $result = $this->searchController->search($request);
        $this->assertEquals('search_company', $result->getName());
        $this->assertArrayHasKey('companies', $result->getData());
    }

    public function test_filter_null_tag()
    {
        $jobs = factory(Job::class, 10)->make();
        $request = new Request();
        $this->jobMock->shouldReceive('getApproveJob')->andReturn($jobs);
        $this->jobMock->shouldReceive('getAppliedJob')->andReturn($jobs);
        $result = $this->searchController->filter($request);
        $this->assertEquals('layouts.filter_job', $result->getName());
        $this->assertArrayHasKey('jobs', $result->getData());
        $this->assertArrayHasKey('appliedJobs', $result->getData());
    }

    public function test_filter_has_tag()
    {
        $jobs = factory(Job::class, 10)->make();
        $request = new Request();
        $request->tag = 1;
        $this->jobMock->shouldReceive('getFilterJob')->with($request)->andReturn($jobs);
        $result = $this->searchController->filter($request);
        $this->assertEquals('layouts.filter_job', $result->getName());
        $this->assertArrayHasKey('jobs', $result->getData());
    }

    public function test_filter_login()
    {
        $user = factory(User::class)->make();
        $this->be($user);
        $jobs = factory(Job::class, 10)->make();
        $request = new Request();
        $request->tag = 1;
        $this->jobMock->shouldReceive('getFilterJob')->with($request)->andReturn($jobs);
        $this->jobMock->shouldReceive('getAppliedJob')->andReturn($jobs);
        $result = $this->searchController->filter($request);
        $this->assertEquals('layouts.filter_job', $result->getName());
        $this->assertArrayHasKey('jobs', $result->getData());
        $this->assertArrayHasKey('appliedJobs', $result->getData());
    }
}
