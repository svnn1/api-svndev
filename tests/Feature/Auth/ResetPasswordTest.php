<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use Illuminate\Http\Response;
use App\Domains\Users\Models\User;
use Illuminate\Support\Facades\Password;
use App\Support\Testing\DatabaseMigrations;

/**
 * Class ResetPasswordTest
 *
 * @package Tests\Feature\Auth
 */
class ResetPasswordTest extends TestCase
{
  use DatabaseMigrations;

  const PASSWORD_RESET_URL = 'auth/password/reset';

  /**
   * @return void
   */
  public function testUserCanResetPasswordWithValidToken(): void
  {
    $this->runDatabaseMigrations();

    $user = factory(User::class)->create([
      'password' => bcrypt('old-password'),
    ]);

    $this->post(self::PASSWORD_RESET_URL, [
      'token'                 => $this->getValidToken($user),
      'email'                 => $user->email,
      'password'              => 'new-awesome-password',
      'password_confirmation' => 'new-awesome-password',
    ])->assertStatus(Response::HTTP_OK);
  }

  /**
   * @return void
   */
  public function testUserCannotResetPasswordWithInvalidToken(): void
  {
    $this->runDatabaseMigrations();

    $user = factory(User::class)->create([
      'password' => bcrypt('old-password'),
    ]);

    $this->post(self::PASSWORD_RESET_URL, [
      'token'                 => $this->getInvalidToken(),
      'email'                 => $user->email,
      'password'              => 'new-awesome-password',
      'password_confirmation' => 'new-awesome-password',
    ])->assertStatus(Response::HTTP_INTERNAL_SERVER_ERROR);
  }

  /**
   * @return void
   */
  public function testUserCannotResetPasswordWithoutProvidingANewPassword(): void
  {
    $this->runDatabaseMigrations();

    $user = factory(User::class)->create([
      'password' => bcrypt('old-password'),
    ]);

    $this->post(self::PASSWORD_RESET_URL, [
      'token'                 => $this->getValidToken($user),
      'email'                 => $user->email,
      'password'              => '',
      'password_confirmation' => '',
    ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
  }


  /**
   * @return void
   */
  public function testUserCannotResetPasswordWithoutProvidingAnEmail(): void
  {
    $this->runDatabaseMigrations();

    $user = factory(User::class)->create([
      'password' => bcrypt('old-password'),
    ]);

    $this->post(self::PASSWORD_RESET_URL, [
      'token'                 => $this->getValidToken($user),
      'email'                 => '',
      'password'              => 'new-awesome-password',
      'password_confirmation' => 'new-awesome-password',
    ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
  }

  /**
   * Create a valid token.
   *
   * @param \App\Domains\Users\Models\User $user
   *
   * @return string
   */
  private function getValidToken(User $user): string
  {
    return Password::broker()->createToken($user);
  }

  /**
   * Return a invalid token.
   *
   * @return string
   */
  private function getInvalidToken(): string
  {
    return 'invalid-token';
  }
}
