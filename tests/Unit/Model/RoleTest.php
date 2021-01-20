<?php

namespace Tests\Unit;

use Tests\TestCase;
use Tests\ModelTestCase;
use App\Models\Role;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RoleTest extends ModelTestCase
{
    protected $role;

    public function setUp() : void
    {
        $this->role = new Role();
        parent::setUp();
    }

    public function tearDown() : void
    {
        $this->role = null;
        parent::tearDown();
    }

    public function test_role_relation()
    {
        $relation = $this->role->users();
        $this->assertHasManyRelation($relation, $this->role);
    }
}
