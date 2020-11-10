<?php


namespace App\Support\Repositories;

use Closure;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Class BaseRepository
 *
 * @package App\Support\Repositories
 */
abstract class BaseRepository
{
  /**
   * Model class for repository
   *
   * @var string
   */
  protected string $model;

  /**
   * Record a new data
   *
   * @param array $data
   *
   * @return \Illuminate\Database\Eloquent\Model
   * @throws \Illuminate\Contracts\Container\BindingResolutionException
   */
  public function create(array $data): Model
  {
    $model = $this->factory($data);

    $this->save($model);

    return $model;
  }

  /**
   * Find a model by id
   *
   * @param string         $id
   * @param array|string[] $columns
   *
   * @return \Illuminate\Database\Eloquent\Model
   * @throws \Illuminate\Contracts\Container\BindingResolutionException
   */
  public function find(string $id, array $columns = ['*']): Model
  {
    return $this->newQuery()->findOrFail($id, $columns);
  }

  /**
   * Find a model by attribute
   *
   * @param string         $attribute
   * @param string         $value
   * @param array|string[] $columns
   *
   * @return \Illuminate\Database\Eloquent\Model
   * @throws \Illuminate\Contracts\Container\BindingResolutionException
   */
  public function findBy(string $attribute, string $value, array $columns = ['*']): Model
  {
    return $this->newQuery()->where($attribute, '=', $value)->firstOrFail($columns);
  }

  /**
   * Find a collection of models by the given query conditions
   *
   * @param array $where
   * @param bool  $or
   *
   * @return \Illuminate\Database\Eloquent\Builder
   * @throws \Illuminate\Contracts\Container\BindingResolutionException
   */
  public function findWhere(array $where, bool $or = FALSE): Builder
  {
    $model = $this->newQuery();

    foreach ($where as $field => $value) {
      if ($value instanceof Closure) {
        $model = (!$or) ? $model->where($value) : $model->orWhere($value);
      } else if (is_array($value)) {
        if (count($value) === 3) {
          [$field, $operator, $search] = $value;

          $model = (!$or) ? $model->where($field, $operator, $search) : $model->orWhere($field, $operator, $search);
        } else if (count($value) === 2) {
          [$field, $search] = $value;

          $model = (!$or) ? $model->where($field, '=', $search) : $model->orWhere($field, '=', $search);
        }
      } else {
        $model = (!$or) ? $model->where($field, '=', $value) : $model->orWhere($field, '=', $value);
      }
    }

    return $model;
  }

  /**
   * Update a record in the database
   *
   * @param \Illuminate\Database\Eloquent\Model $model
   * @param array                               $data
   *
   * @return \Illuminate\Database\Eloquent\Model
   */
  public function update(Model $model, array $data): Model
  {
    $this->setModelData($model, $data);

    $this->save($model);

    return $model;
  }

  /**
   * Delete a record from the database
   *
   * @param \Illuminate\Database\Eloquent\Model $model
   *
   * @return \Illuminate\Database\Eloquent\Model
   * @throws \Exception
   */
  public function delete(Model $model): Model
  {
    $model->delete();

    return $model;
  }

  /**
   * Create a new instance to populate the model with an array of attributes in a given model
   *
   * @param array $data
   *
   * @return \Illuminate\Database\Eloquent\Model
   * @throws \Illuminate\Contracts\Container\BindingResolutionException
   */
  public function factory(array $data): Model
  {
    $model = $this->newQuery()->getModel()->newInstance();

    $this->setModelData($model, $data);

    return $model;
  }

  /**
   * Get a new query builder for the model's table
   *
   * @return \Illuminate\Database\Eloquent\Builder
   * @throws \Illuminate\Contracts\Container\BindingResolutionException
   */
  public function newQuery(): Builder
  {
    return app()->make($this->model)->newQuery();
  }

  /**
   * Performs the save method of the model
   *
   * @param \Illuminate\Database\Eloquent\Model $model
   *
   * @return bool
   */
  public function save(Model $model): bool
  {
    return $model->save();
  }

  /**
   * Fill the model with an array of attributes
   *
   * @param \Illuminate\Database\Eloquent\Model $model
   * @param array                               $data
   *
   * @return void
   */
  protected function setModelData(Model $model, array $data): void
  {
    $model->fill($data);
  }
}
