<?php

namespace Tests\Unit;

use Tests\ModelTestCase;
use App\Models\Company;
use App\Models\User;

class CompanyTest extends ModelTestCase
{
    public function test_company()
    {
        $company = new Company();
        $fillable = [
            'name',
            'address',
            'website',
            'introduce',
            'user_id',
        ];

        $this->runConfigurationAssertions($company, $fillable);

        $relationUser = $company->user();
        $this->assertBeLongsToRelation($relationUser, new User());

        $relationJobs = $company->jobs();
        $this->assertHasManyRelation($relationJobs, $company);

        $relationImages = $company->images();
        $this->assertMorphManyRelation($relationImages, $company, 'imageable_id');
    }
}
