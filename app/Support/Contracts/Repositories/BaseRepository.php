<?php

namespace App\Support\Contracts\Repositories;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * Interface BaseRepository
 *
 * @package App\Support\Contracts\Repositories
 */
interface BaseRepository
{
  /**
   * Save a new model in database.
   *
   * @param array $data
   *
   * @return \Illuminate\Database\Eloquent\Model
   * @throws \Illuminate\Contracts\Container\BindingResolutionException
   */
  public function create(array $data): Model;

  /**
   * Find a model by id.
   *
   * @param string $id
   * @param array  $columns
   *
   * @return \Illuminate\Database\Eloquent\Model
   * @throws \Illuminate\Contracts\Container\BindingResolutionException
   */
  public function find(string $id, array $columns = ['*']): Model;

  /**
   * Find a model by attribute.
   *
   * @param string $attribute
   * @param string $value
   * @param array  $columns
   *
   * @return \Illuminate\Database\Eloquent\Model
   * @throws \Illuminate\Contracts\Container\BindingResolutionException
   */
  public function findBy(string $attribute, string $value, array $columns = ['*']): Model;

  /**
   * Find a collection of models by the given query conditions.
   *
   * @param array $where
   * @param bool  $or
   *
   * @return \Illuminate\Database\Eloquent\Builder
   * @throws \Illuminate\Contracts\Container\BindingResolutionException
   */
  public function findWhere(array $where, bool $or = FALSE): Builder;

  /**
   * Update a record in the database.
   *
   * @param \Illuminate\Database\Eloquent\Model $model
   * @param array                               $data
   *
   * @return \Illuminate\Database\Eloquent\Model
   */
  public function update(Model $model, array $data): Model;

  /**
   * Delete a record from the database.
   *
   * @param \Illuminate\Database\Eloquent\Model $model
   *
   * @return \Illuminate\Database\Eloquent\Model
   * @throws \Exception
   */
  public function delete(Model $model): Model;

  /**
   * Create a new instance to populate the model with an array of attributes in a given model.
   *
   * @param array $data
   *
   * @return \Illuminate\Database\Eloquent\Model
   * @throws \Illuminate\Contracts\Container\BindingResolutionException
   */
  public function factory(array $data): Model;

  /**
   * Get a new query builder for the model's table.
   *
   * @return \Illuminate\Database\Eloquent\Builder
   * @throws \Illuminate\Contracts\Container\BindingResolutionException
   */
  public function newQuery(): Builder;

  /**
   * Performs the save method of the model.
   *
   * @param \Illuminate\Database\Eloquent\Model $model
   *
   * @return bool
   */
  public function save(Model $model): bool;
}
