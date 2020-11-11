<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use Illuminate\Http\Response;
use App\Support\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;

/**
 * Class RegisterTest
 *
 * @package Tests\Feature\Auth
 */
class RegisterTest extends TestCase
{
  use DatabaseMigrations, WithFaker;

  /**
   * Set route path for register
   *
   * @var string
   */
  const REGISTER_URL = '/auth/register';

  /**
   * User can register
   *
   * @return void
   */
  public function testUserCanRegister(): void
  {
    $this->runDatabaseMigrations();

    $this->post(self::REGISTER_URL, $data = [
      'name'                  => $this->faker->firstName,
      'email'                 => $this->faker->safeEmail,
      'password'              => 'i-love-laravel',
      'password_confirmation' => 'i-love-laravel',
    ])->assertStatus(Response::HTTP_CREATED);

    $this->assertDatabaseHas('users', [
      'name'  => $data['name'],
      'email' => $data['email'],
    ]);
  }

  /**
   * User cannot register without name
   *
   * @return void
   */
  public function testUserCannotRegisterWithoutName(): void
  {
    $this->runDatabaseMigrations();

    $this->post(self::REGISTER_URL, [
      'first_name'            => '',
      'email'                 => $this->faker->safeEmail,
      'password'              => 'i-love-laravel',
      'password_confirmation' => 'i-love-laravel',
    ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

    $this->assertGuest('api');
  }

  /**
   * User cannot register without email
   *
   * @return void
   */
  public function testUserCannotRegisterWithoutEmail(): void
  {
    $this->runDatabaseMigrations();

    $this->post(self::REGISTER_URL, [
      'name'                  => $this->faker->firstName,
      'email'                 => '',
      'password'              => 'i-love-laravel',
      'password_confirmation' => 'i-love-laravel',
    ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

    $this->assertGuest('api');
  }

  /**
   * User cannot register with invalid email
   *
   * @return void
   */
  public function testUserCannotRegisterWithInvalidEmail(): void
  {
    $this->runDatabaseMigrations();

    $this->post(self::REGISTER_URL, [
      'name'                  => $this->faker->name,
      'email'                 => 'invalid-email',
      'password'              => 'i-love-laravel',
      'password_confirmation' => 'i-love-laravel',
    ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

    $this->assertGuest('api');
  }

  /**
   * User cannot register without password
   *
   * @return void
   */
  public function testUserCannotRegisterWithoutPassword(): void
  {
    $this->runDatabaseMigrations();

    $this->post(self::REGISTER_URL, [
      'name'                  => $this->faker->name,
      'email'                 => $this->faker->safeEmail,
      'password'              => '',
      'password_confirmation' => '',
    ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

    $this->assertGuest('api');
  }

  /**
   * User cannot register without password confirmation
   *
   * @return void
   */
  public function testUserCannotRegisterWithoutPasswordConfirmation(): void
  {
    $this->runDatabaseMigrations();

    $this->post(self::REGISTER_URL, [
      'name'                  => $this->faker->name,
      'email'                 => $this->faker->safeEmail,
      'password'              => 'i-love-laravel',
      'password_confirmation' => '',
    ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

    $this->assertGuest('api');
  }

  /**
   * User cannot register without password not matching
   *
   * @return void
   */
  public function testUserCannotRegisterWithPasswordsNotMatching(): void
  {
    $this->runDatabaseMigrations();

    $this->post(self::REGISTER_URL, [
      'name'                  => $this->faker->name,
      'email'                 => $this->faker->safeEmail,
      'password'              => 'i-love-laravel',
      'password_confirmation' => 'i-love-php',
    ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

    $this->assertGuest('api');
  }
}
