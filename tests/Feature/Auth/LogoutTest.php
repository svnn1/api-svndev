<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use Illuminate\Http\Response;
use App\Domains\Users\Models\User;
use App\Support\Testing\DatabaseMigrations;

/**
 * Class LogoutTest
 *
 * @package Tests\Feature\Auth
 */
class LogoutTest extends TestCase
{
  use DatabaseMigrations;

  /**
   * Set route path for login
   *
   * @var string
   */
  const LOGIN_URL = '/auth/login';

  /**
   * Set route path for login
   *
   * @var string
   */
  const LOGOUT_URL = '/auth/logout';


  /**
   * User can logout
   *
   * @return void
   */
  public function testUserCanLogout(): void
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

    $this->post(self::LOGOUT_URL)->assertStatus(Response::HTTP_OK);

    $this->assertGuest('api');
  }

  /**
   * User cannot logout when not authenticated
   *
   * @return void
   */
  public function testUserCannotLogoutWhenNotAuthenticated(): void
  {
    $this->runDatabaseMigrations();

    $this->post(self::LOGOUT_URL)->assertStatus(Response::HTTP_UNAUTHORIZED);

    $this->assertGuest('api');
  }

  /**
   * User cannot make more than five attempts in one minute
   *
   * @return void
   */
  public function testUserCannotMakeMoreThanFiveAttemptsInOneMinute(): void
  {
    $this->runDatabaseMigrations();

    $user = factory(User::class)->create([
      'password' => bcrypt($password = 'i-love-laravel'),
    ]);

    $response = NULL;

    foreach (range(0, 5) as $attempt) {
      $response = $this->post(self::LOGIN_URL, [
        'email'    => $user->email,
        'password' => 'invalid-password',
      ]);
    }

    $response->assertStatus(Response::HTTP_TOO_MANY_REQUESTS);

    $this->assertGuest('api');
  }
}
