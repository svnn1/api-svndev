<?php

namespace App\Units\Auth\Http\Routes;

use App\Support\Http\Routing\RouteFile;
use App\Units\Auth\Http\Controllers\LoginController;
use App\Units\Auth\Http\Controllers\LogoutController;
use App\Units\Auth\Http\Controllers\RegisterController;
use App\Units\Auth\Http\Controllers\RefreshTokenController;
use App\Units\Auth\Http\Controllers\VerificationController;
use App\Units\Auth\Http\Controllers\ResetPasswordController;
use App\Units\Auth\Http\Controllers\ForgotPasswordController;

/**
 * Class Api
 *
 * @package App\Units\Auth\Http\Routes
 */
class Api extends RouteFile
{
  /**
   * Define routes.
   *
   * @return void
   */
  public function routes(): void
  {
    $this->registerAuthGroupRoutes();
  }

  protected function registerAuthGroupRoutes(): void
  {
    $this->router->group(['prefix' => '/auth/'], function () {
      $this->registerLoginRoutes();
      $this->registerSignUpRoutes();
      $this->registerLogoutRoutes();
      $this->registerRefreshToken();
      $this->registerPasswordRoutes();
      $this->registerEmailVerificationRoutes();
    });
  }

  protected function registerLoginRoutes(): void
  {
    $this->router->post('/login', [LoginController::class, 'login'])->name('login');
  }

  protected function registerSignUpRoutes(): void
  {
    $this->router->post('/register', [RegisterController::class, 'register'])->name('register');
  }

  protected function registerLogoutRoutes(): void
  {
    $this->router->post('/logout', [LogoutController::class, 'logout'])
      ->name('logout')
      ->middleware('auth:api');
  }

  protected function registerRefreshToken(): void
  {
    $this->router->post('/refresh', [RefreshTokenController::class, 'refresh'])
      ->name('auth:refresh')
      ->middleware('auth:api');
  }

  protected function registerPasswordRoutes(): void
  {
    $this->router->post('/password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])
      ->name('password.email');
    $this->router->post('/password/reset', [ResetPasswordController::class, 'reset'])
      ->name('password.reset');
  }

  protected function registerEmailVerificationRoutes(): void
  {
    $this->router->get('/email/resend', [VerificationController::class, 'resend'])
      ->name('verification.resend')
      ->middleware('auth:api');
    $this->router->get('/email/verify/{id}', [VerificationController::class, 'verify'])
      ->name('verification.verify')
      ->middleware('auth:api');
  }
}
