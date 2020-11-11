<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use Illuminate\Http\Response;
use App\Domains\Users\Models\User;
use Illuminate\Support\Facades\URL;
use App\Support\Testing\DatabaseMigrations;
use Illuminate\Support\Facades\Notification;
use Illuminate\Auth\Notifications\VerifyEmail;

/**
 * Class EmailVerificationTest
 *
 * @package Tests\Feature\Auth
 */
class EmailVerificationTest extends TestCase
{
  use DatabaseMigrations;

  /**
   * Set route path for verification
   *
   * @var string
   */
  const VERIFICATION_ROUTE = 'verification.verify';

  /**
   * Set route path for resend verification
   *
   * @var string
   */
  const RESEND_ROUTE = 'verification.resend';

  /**
   * User cannot verify others
   *
   * @return void
   */
  public function testUserCannotVerifyOthers(): void
  {
    $this->runDatabaseMigrations();

    $user = factory(User::class)->create([
      'email_verified_at' => NULL,
    ]);

    $otherUser = factory(User::class)->create([
      'email_verified_at' => NULL,
    ]);

    $this->actingAs($user, 'api')
      ->get($this->validVerificationVerifyRoute($otherUser))
      ->assertStatus(Response::HTTP_FORBIDDEN);

    $this->assertFalse($otherUser->fresh()->hasVerifiedEmail());
  }

  /**
   * Forbidden is returned when signature is invalid in verification verify route
   *
   * @return void
   */
  public function testForbiddenIsReturnedWhenSignatureIsInvalidInVerificationVerifyRoute(): void
  {
    $this->runDatabaseMigrations();

    $user = factory(User::class)->create([
      'email_verified_at' => now(),
    ]);

    $this->actingAs($user, 'api')
      ->get($this->invalidVerificationVerifyRoute($user))
      ->assertStatus(Response::HTTP_FORBIDDEN);
  }

  /**
   * User can verify yourself
   *
   * @return void
   */
  public function testUserCanVerifyYourself(): void
  {
    $this->runDatabaseMigrations();

    $user = factory(User::class)->create([
      'email_verified_at' => NULL,
    ]);

    $this->actingAs($user, 'api')
      ->get($this->validVerificationVerifyRoute($user))
      ->assertStatus(Response::HTTP_OK);

    $this->assertNotNull($user->fresh()->email_verified_at);
  }

  /**
   * Guest cannot resend a verification email
   *
   * @return void
   */
  public function testGuestCannotResendAVerificationEmail(): void
  {
    $this->get($this->verificationResendRoute())
      ->assertStatus(Response::HTTP_UNAUTHORIZED);
  }

  /**
   * User can resend a verification email
   *
   * @return void
   */
  public function testUserCanResendAVerificationEmail(): void
  {
    $this->runDatabaseMigrations();

    Notification::fake();

    $user = factory(User::class)->create([
      'email_verified_at' => NULL,
    ]);

    $this->actingAs($user, 'api')
      ->get($this->verificationResendRoute());

    Notification::assertSentTo($user, VerifyEmail::class);
  }

  /**
   * Return a valid verification verify route
   *
   * @param \App\Domains\Users\Models\User $user
   *
   * @return string
   */
  private function validVerificationVerifyRoute(User $user): string
  {
    return URL::signedRoute(self::VERIFICATION_ROUTE, [
      'id'   => $user->id,
      'hash' => sha1($user->getEmailForVerification()),
    ]);
  }

  /**
   * Return a invalid verification verify route
   *
   * @param \App\Domains\Users\Models\User $user
   *
   * @return string
   */
  private function invalidVerificationVerifyRoute(User $user): string
  {
    return route(self::VERIFICATION_ROUTE, [
      'id'   => $user->id,
      'hash' => 'invalid-hash',
    ]);
  }

  /**
   * Return a verification resend route
   *
   * @return string
   */
  private function verificationResendRoute(): string
  {
    return route(self::RESEND_ROUTE);
  }
}
