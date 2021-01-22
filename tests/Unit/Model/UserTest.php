<?php

namespace Tests\Unit;

use Tests\TestCase;
use Tests\ModelTestCase;
use App\Models\User;
use App\Models\Job;
use App\Models\Role;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserTest extends ModelTestCase
{
    protected $user;

    public function setUp() : void
    {
        $this->user = new User();
        parent::setUp();
    }

    public function tearDown() : void
    {
        $this->user = null;
        parent::tearDown();
    }

    public function test_fillable()
    {
        $fillable = [
            'name',
            'email',
            'password',
            'cv',
            'introduce',
            'role_id',
            'status',
        ];
        $this->assertEquals($fillable, $this->user->getFillable());
    }

    public function test_role_relation()
    {
        $relation = $this->user->role();
        $this->assertBelongsToRelation($relation, new Role());
    }

    public function test_image_relation()
    {
        $relation = $this->user->image();
        $this->assertMorphOneRelation($relation, $this->user, 'imageable_id');
    }

    public function test_tag_relation()
    {
        $relation = $this->user->tags();
        $this->assertMorphToManyRelation($relation, new Tag(), 'taggable_id');
    }

    public function test_company_relation()
    {
        $relation = $this->user->company();
        $this->assertHasOneRelation($relation, $this->user);
    }

    public function test_job_relation()
    {
        $relation = $this->user->jobs();
        $this->assertBelongsToManyRelation($relation, $this->user, new Job());
    }
}
