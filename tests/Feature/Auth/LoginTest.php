<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use Illuminate\Http\Response;
use App\Domains\Users\Models\User;
use App\Support\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\WithFaker;

/**
 * Class LoginTest
 *
 * @package Tests\Feature\Auth
 */
class LoginTest extends TestCase
{
  use DatabaseMigrations, WithFaker;

  /**
   * Set route path for login
   *
   * @var string
   */
  const LOGIN_URL = '/auth/login';

  /**
   * User can authenticate with correct credentials
   *
   * @return void
   */
  public function testUserCanLoginWithCorrectCredentials(): void
  {
    $this->runDatabaseMigrations();

    $user = factory(User::class)->create([
      'password' => bcrypt($password = 'i-love-laravel'),
    ]);

    $this->post(self::LOGIN_URL, [
      'email'    => $user->email,
      'password' => $password,
    ])->assertStatus(Response::HTTP_CREATED);

    $this->assertAuthenticated('api');
    $this->assertAuthenticatedAs($user, 'api');
  }

  /**
   * User cannot authenticate with incorrect password
   *
   * @return void
   */
  public function testUserCannotLoginWithIncorrectPassword(): void
  {
    $this->runDatabaseMigrations();

    $user = factory(User::class)->create();

    $response = $this->post(self::LOGIN_URL, [
      'email'    => $user->email,
      'password' => 'invalid-password',
    ])->assertStatus(Response::HTTP_UNAUTHORIZED);

    $response->assertStatus(Response::HTTP_UNAUTHORIZED)->assertJson([
      'error' => [
        'message' => "These credentials do not match our records.",
      ],
    ]);

    $this->assertGuest('api');
  }

  /**
   * User cannot login with email that does not exist
   *
   * @return void
   */
  public function testUserCannotLoginWithEmailThatDoesNotExist(): void
  {
    $this->runDatabaseMigrations();

    $response = $this->post(self::LOGIN_URL, [
      'email'    => 'silvano@svndev.com.br',
      'password' => 'invalid-password',
    ])->assertStatus(Response::HTTP_UNAUTHORIZED);

    $response->assertStatus(Response::HTTP_UNAUTHORIZED)->assertJson([
      'error' => [
        'message' => "These credentials do not match our records.",
      ],
    ]);

    $this->assertGuest('api');
  }
}
