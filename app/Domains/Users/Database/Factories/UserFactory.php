<?php

namespace App\Domains\Users\Database\Factories;

use App\Domains\Users\Models\User;
use App\Support\Domain\ModelFactory;

/**
 * Class UserFactory
 *
 * @package App\Domains\Users\Database\Factories
 */
class UserFactory extends ModelFactory
{
  /**
   * The name of the factory's corresponding model.
   *
   * @var string
   */
  protected string $model = User::class;

  /**
   * Define the model's default state.
   *
   * @return array
   */
  public function fields(): array
  {
    return [
      'name'     => $this->faker->name,
      'email'    => $this->faker->safeEmail,
      'password' => bcrypt('secret'),
    ];
  }
}
