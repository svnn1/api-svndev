<?php

namespace App\Support\Domain;

use Faker\Generator;
use Illuminate\Database\Eloquent\Factory;

/**
 * Class ModelFactory
 *
 * @package App\Support\Domain
 */
abstract class ModelFactory
{
  /**
   * @var \Illuminate\Database\Eloquent\Factory
   */
  protected Factory $factory;

  /**
   * @var string
   */
  protected string $model;

  /**
   * @var \Faker\Generator
   */
  protected Generator $faker;

  /**
   * ModelFactory constructor.
   *
   * @throws \Illuminate\Contracts\Container\BindingResolutionException
   */
  public function __construct()
  {
    $this->factory = app()->make(Factory::class);
    $this->faker   = app()->make(Generator::class);
  }

  /**
   * Define a class with a given set of attributes
   *
   * @return void
   */
  public function define(): void
  {
    $this->factory->define($this->model, fn() => $this->fields());
  }

  /**
   * Define the model's default state.
   *
   * @return array
   */
  abstract public function fields(): array;
}
