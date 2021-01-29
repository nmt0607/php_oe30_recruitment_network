<?php

namespace Tests;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Database\Eloquent\Relations\MorphToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class ModelTestCase extends TestCase
{
    /**
     * @param Model $model
     * @param array $fillable
     * @param array $guarded
     * @param array $hidden
     * @param array $visible
     * @param array $casts
     * @param array $dates
     * @param string $collectionClass
     * @param null $table
     * @param string $primaryKey
     * @param null $connection
     *
     * - `$fillable` -> `getFillable()`
     * - `$guarded` -> `getGuarded()`
     * - `$table` -> `getTable()`
     * - `$primaryKey` -> `getKeyName()`
     * - `$connection` -> `getConnectionName()`: in case multiple connections exist.
     * - `$hidden` -> `getHidden()`
     * - `$visible` -> `getVisible()`
     * - `$casts` -> `getCasts()`: note that method appends incrementing key.
     * - `$dates` -> `getDates()`: note that method appends `[static::CREATED_AT, static::UPDATED_AT]`.
     * - `newCollection()`: assert collection is exact type. Use `assertEquals` on `get_class()` result, but not `assertInstanceOf`.
     */
    protected function runConfigurationAssertions(
        Model $model,
        $fillable = [],
        $hidden = [],
        $guarded = ['*'],
        $visible = [],
        $casts = ['id' => 'int'],
        $dates = ['created_at', 'updated_at'],
        $collectionClass = Collection::class,
        $table = null,
        $primaryKey = 'id',
        $connection = null
    ) {
        $this->assertEquals($fillable, $model->getFillable());
        $this->assertEquals($guarded, $model->getGuarded());
        $this->assertEquals($hidden, $model->getHidden());
        $this->assertEquals($visible, $model->getVisible());
        $this->assertEquals($casts, $model->getCasts());
        $this->assertEquals($dates, $model->getDates());
        $this->assertEquals($primaryKey, $model->getKeyName());

        $c = $model->newCollection();
        $this->assertEquals($collectionClass, get_class($c));
        $this->assertInstanceOf(Collection::class, $c);

        if ($connection !== null) {
            $this->assertEquals($connection, $model->getConnectionName());
        }

        if ($table !== null) {
            $this->assertEquals($table, $model->getTable());
        }
    }

    /**
     * @param HasMany $relation
     * @param Model $model
     * @param Model $related
     * @param string $key
     * @param string $parent
     * @param \Closure $queryCheck
     *
     * - `getQuery()`: assert query has not been modified or modified properly.
     * - `getForeignKey()`: any `HasOneOrMany` or `BelongsTo` relation, but key type differs (see documentaiton).
     * - `getQualifiedParentKeyName()`: in case of `HasOneOrMany` relation, there is no `getLocalKey()` method, so this one should be asserted.
     */
    protected function assertHasManyRelation($relation, Model $model, $key = null, $parent = null)
    {
        $this->assertInstanceOf(HasMany::class, $relation);

        if (is_null($key)) {
            $key = $model->getForeignKey();
        }

        $this->assertEquals($key, $relation->getForeignKeyName());

        if (is_null($parent)) {
            $parent = $model->getKeyName();
        }

        $this->assertEquals($model->getTable() . '.' . $parent, $relation->getQualifiedParentKeyName());
    }

    /**
     * @param BelongsTo $relation
     * @param Model $model
     * @param Model $related
     * @param string $key
     * @param string $owner
     * @param \Closure $queryCheck
     *
     * - `getQuery()`: assert query has not been modified or modified properly.
     * - `getForeignKey()`: any `HasOneOrMany` or `BelongsTo` relation, but key type differs (see documentaiton).
     * - `getOwnerKey()`: `BelongsTo` relation and its extendings.
     */
    protected function assertBelongsToRelation($relation, Model $related, $key = null, $owner = null)
    {
        $this->assertInstanceOf(BelongsTo::class, $relation);
        if (is_null($key)) {
            $key = $related->getForeignKey();
        }
        $this->assertEquals($key, $relation->getForeignKeyName());

        if (is_null($owner)) {
            $owner = $related->getKeyName();
        }
        $this->assertEquals($owner, $relation->getOwnerKeyName());
    }

    protected function assertMorphManyRelation($relation, Model $model, $key, $parent = null)
    {
        $this->assertInstanceOf(MorphMany::class, $relation);

        $this->assertEquals($key, $relation->getForeignKeyName());

        if (is_null($parent)) {
            $parent = $model->getKeyName();
        }

        $this->assertEquals($model->getTable() . '.' . $parent, $relation->getQualifiedParentKeyName());
    }

    protected function assertMorphToRelation($relation)
    {
        $this->assertInstanceOf(MorphTo::class, $relation);
    }

    protected function assertHasOneRelation($relation, Model $model, $key = null, $parent = null)
    {
        $this->assertInstanceOf(HasOne::class, $relation);

        if (is_null($key)) {
            $key = $model->getForeignKey();
        }

        $this->assertEquals($key, $relation->getForeignKeyName());

        if (is_null($parent)) {
            $parent = $model->getKeyName();
        }

        $this->assertEquals($model->getTable() . '.' . $parent, $relation->getQualifiedParentKeyName());
    }

    protected function assertBelongsToManyRelation($relation, Model $model, Model $related, $key = null, $owner = null)
    {
        $this->assertInstanceOf(BelongsToMany::class, $relation);
        if (is_null($key)) {
            $key = $model->getForeignKey();
        }
        $this->assertEquals($relation->getTable() . '.' . $key, $relation->getQualifiedForeignPivotKeyName());

        if (is_null($owner)) {
            $owner = $related->getForeignKey();
        }
        $this->assertEquals($relation->getTable() . '.' . $owner, $relation->getQualifiedRelatedPivotKeyName());
    }

    protected function assertMorphToManyRelation($relation, Model $related, $key, $owner = null)
    {
        $this->assertInstanceOf(MorphToMany::class, $relation);

        $this->assertEquals($relation->getTable() . '.' . $key, $relation->getQualifiedForeignPivotKeyName());

        if (is_null($owner)) {
            $owner = $related->getForeignKey();
        }

        $this->assertEquals($relation->getTable() . '.' . $owner, $relation->getQualifiedRelatedPivotKeyName());
    }

    protected function assertHasManyThourghRelation($relation)
    {
        $this->assertInstanceOf(HasManyThrough::class, $relation);
    }

    protected function assertMorphOneRelation($relation, Model $model, $key, $parent = null)
    {
        $this->assertInstanceOf(MorphOne::class, $relation);

        $this->assertEquals($key, $relation->getForeignKeyName());

        if (is_null($parent)) {
            $parent = $model->getKeyName();
        }

        $this->assertEquals($model->getTable() . '.' . $parent, $relation->getQualifiedParentKeyName());
    }

    protected function assertMorphedByManyRelation($relation, Model $model, $owner, $key = null)
    {
        $this->assertInstanceOf(MorphToMany::class, $relation);
        if (is_null($key)) {
            $key = $model->getForeignKey();
        }
        $this->assertEquals($relation->getTable() . '.' . $key, $relation->getQualifiedForeignPivotKeyName());
        $this->assertEquals($relation->getTable() . '.' . $owner, $relation->getQualifiedRelatedPivotKeyName());
    }
}
