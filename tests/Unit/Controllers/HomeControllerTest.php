<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\Job;
use App\Models\Tag;
use App\Models\User;
use Mockery;
use App\Http\Controllers\HomeController;
use App\Repositories\Job\JobRepositoryInterface;
use Illuminate\Http\Request;

class HomeControllerTest extends TestCase
{
    public function setUp() : void
    {
        parent::setUp();
        $this->jobMock = Mockery::mock(JobRepositoryInterface::class)->makePartial();
        $this->homeController = new HomeController($this->jobMock);
    }

    public function tearDown() : void
    {
        Mockery::close();
        unset($this->searchController);
        parent::tearDown();
    }

    public function test_index_login_has_tag()
    {
        $user = factory(User::class)->make();
        $this->be($user);
        $jobs = factory(Job::class, 10)->make();
        $tags = factory(Tag::class, 10)->make();
        $tags->id = 1;
        $this->jobMock->shouldReceive('getAllJobs')->andReturn($jobs);
        $this->jobMock->shouldReceive('getAppliedJob')->andReturn($jobs);
        $this->jobMock->shouldReceive('getSkill')->andReturn($tags);
        $this->jobMock->shouldReceive('getLang')->andReturn($tags);
        $this->jobMock->shouldReceive('getSuitableJob')->andReturn($jobs);
        $result = $this->homeController->index();
        $this->assertEquals('home', $result->getName());
        $this->assertArrayHasKey('allJobs', $result->getData());
        $this->assertArrayHasKey('newJobs', $result->getData());
        $this->assertArrayHasKey('appliedJobs', $result->getData());
        $this->assertArrayHasKey('suitableJobs', $result->getData());
    }

    public function test_index_login_null_tag()
    {
        $user = factory(User::class)->make();
        $this->be($user);
        $jobs = factory(Job::class, 10)->make();
        $this->jobMock->shouldReceive('getAllJobs')->andReturn($jobs);
        $this->jobMock->shouldReceive('getAppliedJob')->andReturn($jobs);
        $this->jobMock->shouldReceive('getSkill')->andReturn(false);
        $this->jobMock->shouldReceive('getLang')->andReturn(false);
        $result = $this->homeController->index();
        $this->assertEquals('home', $result->getName());
        $this->assertArrayHasKey('allJobs', $result->getData());
        $this->assertArrayHasKey('newJobs', $result->getData());
        $this->assertArrayHasKey('appliedJobs', $result->getData());
    }

    public function test_index_not_login()
    {
        $jobs = factory(Job::class, 10)->make();
        $this->jobMock->shouldReceive('getAllJobs')->andReturn($jobs);
        $result = $this->homeController->index();
        $this->assertEquals('home', $result->getName());
        $this->assertArrayHasKey('allJobs', $result->getData());
        $this->assertArrayHasKey('newJobs', $result->getData());
    }
}
