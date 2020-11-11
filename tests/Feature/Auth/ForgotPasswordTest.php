<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use Illuminate\Http\Response;
use App\Domains\Users\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Support\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\ResetPassword;

/**
 * Class ForgotPasswordTest
 *
 * @package Tests\Feature\Auth
 */
class ForgotPasswordTest extends TestCase
{
  use DatabaseMigrations;

  /**
   * Set route path for password email
   *
   * @var string
   */
  const PASSWORD_EMAIL_URL = 'auth/password/email';

  /**
   * @return void
   */
  public function testUserReceivesAnEmailWithAPasswordResetLink(): void
  {
    $this->runDatabaseMigrations();

    Notification::fake();

    $user = factory(User::class)->create();

    $this->post(self::PASSWORD_EMAIL_URL, [
      'email' => $user->email,
    ])->assertStatus(Response::HTTP_OK);

    $token = DB::table('password_resets')
      ->where('email', '=', $user->email)
      ->first();

    $this->assertNotNull($token);

    $this->assertDatabaseHas('password_resets', [
      'email' => $user->email,
    ]);

    Notification::assertSentTo($user, ResetPassword::class, function ($notification) use ($token) {
      return Hash::check($notification->token, $token->token);
    });
  }

  /**
   * @return void
   */
  public function testUserDoesNotReceiveEmailWhenNotRegistered(): void
  {
    $this->runDatabaseMigrations();

    Notification::fake();

    $this->post(self::PASSWORD_EMAIL_URL, [
      'email' => 'contact@svndev.com.br',
    ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);

    Notification::assertNotSentTo(factory(User::class)->make([
      'email' => 'nobody@example.com',
    ]), ResetPassword::class);
  }

  /**
   * @return void
   */
  public function testEmailIsRequired(): void
  {
    $this->runDatabaseMigrations();

    $this->post(self::PASSWORD_EMAIL_URL, [
      'email' => '',
    ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
      ->assertJson([
        'error' => [
          'status'  => Response::HTTP_UNPROCESSABLE_ENTITY,
          'message' => 'The given data was invalid.',
          'errors'  => [
            'email' => [
              0 => 'The email field is required.',
            ],
          ],
        ],
      ]);
  }

  /**
   * @return void
   */
  public function testEmailIsAValidEmail(): void
  {
    $this->runDatabaseMigrations();

    $this->post(self::PASSWORD_EMAIL_URL, [
      'email' => 'invalid-email',
    ])->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY)
      ->assertJson([
        'error' => [
          'status'  => Response::HTTP_UNPROCESSABLE_ENTITY,
          'message' => 'The given data was invalid.',
          'errors'  => [
            'email' => [
              0 => 'The email must be a valid email address.',
            ],
          ],
        ],
      ]);
  }
}
