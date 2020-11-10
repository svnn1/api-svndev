<?php

namespace App\Units\Auth\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Password;
use Illuminate\Auth\Events\PasswordReset;
use App\Support\Http\Controllers\Controller;
use App\Units\Auth\Http\Requests\ResetPasswordRequest;

/**
 * Class ResetPasswordController
 *
 * @package App\Units\Auth\Http\Controllers
 */
class ResetPasswordController extends Controller
{
  /**
   * Reset password.
   *
   * @param \App\Units\Auth\Http\Requests\ResetPasswordRequest $request
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function reset(ResetPasswordRequest $request): JsonResponse
  {
    $response = Password::broker()->reset(
      $request->only(
        'email', 'password', 'password_confirmation', 'token'
      ), function ($user) use ($request) {
      $user->password = bcrypt($request->get('password'));
      $user->setRememberToken(Str::random(60));
      $user->save();

      event(new PasswordReset($user));
    });

    return $response == Password::PASSWORD_RESET
      ? $this->sendResetResponse($request, $response)
      : $this->sendResetFailedResponse($request, $response);
  }

  /**
   * Get the response for a successful password reset.
   *
   * @param \Illuminate\Http\Request $request
   * @param string                   $response
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function sendResetResponse(Request $request, string $response): JsonResponse
  {
    return response()->json([
      'data' => [
        'status' => trans($response),
      ],
    ], Response::HTTP_OK);
  }

  /**
   * Get the response for a failed password reset.
   *
   * @param \Illuminate\Http\Request $request
   * @param string                   $response
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function sendResetFailedResponse(Request $request, string $response): JsonResponse
  {
    return response()->json([
      'error' => [
        'status'  => Response::HTTP_INTERNAL_SERVER_ERROR,
        'message' => trans($response),
      ],
    ], Response::HTTP_INTERNAL_SERVER_ERROR);
  }
}
