<?php

namespace Tests\Unit;

use Tests\TestCase;
use Tests\ModelTestCase;
use App\Models\Tag;
use App\Models\User;
use App\Models\Job;

class TagTest extends ModelTestCase
{
    protected $tag;

    public function setUp() : void
    {
        $this->tag = new Tag();
        parent::setUp();
    }

    public function tearDown() : void
    {
        $this->tag = null;
        parent::tearDown();
    }

    public function test_user_relation()
    {
        $relation = $this->tag->users();
        $this->assertMorphedByManyRelation($relation, $this->tag, 'taggable_id');
    }

    public function test_job_relation()
    {
        $relation = $this->tag->jobs();
        $this->assertMorphedByManyRelation($relation, $this->tag, 'taggable_id');
    }
}
