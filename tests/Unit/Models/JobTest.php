<?php

namespace Tests\Unit;

use Tests\ModelTestCase;
use App\Models\Job;
use App\Models\Company;
use App\Models\User;
use App\Models\Tag;

class JobTest extends ModelTestCase
{
    public function test_job_relation()
    {
        $job = new Job();
        $fillable = [
            'title',
            'description',
            'experience',
            'salary',
            'company_id',
            'status',
        ];

        $this->runConfigurationAssertions($job, $fillable);

        $relationImage = $job->images();
        $this->assertHasManyThourghRelation($relationImage);

        $relationJob = $job->users();
        $this->assertBeLongsToManyRelation($relationJob, $job, new User());

        $relationTag = $job->tags();
        $this->assertMorphToManyRelation($relationTag, new Tag(), 'taggable_id');

        $relationCompany = $job->company();
        $this->assertBeLongsToRelation($relationCompany, new Company());
    }
}
