<?php

namespace Tests\Unit;

use Tests\ModelTestCase;
use App\Models\Image;

class ImageTest extends ModelTestCase
{
    public function test_image_relation()
    {
        $image = new Image();
        $fillable = [
            'url',
            'imageable_id',
            'imageable_type',
            'type',
        ];

        $this->runConfigurationAssertions($image, $fillable);

        $relation = $image->imageable();
        $this->assertMorphToRelation($relation);
    }
}
