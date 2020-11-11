<?php

namespace App\Units\Auth\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Units\Auth\Traits\ThrottlesLogins;
use App\Support\Http\Controllers\Controller;

/**
 * Class LoginController
 *
 * @package App\Units\Auth\Http\Controllers
 */
class LoginController extends Controller
{
  use ThrottlesLogins;

  /**
   * Issue a JWT token when valid login credentials are presented
   *
   * @param \Illuminate\Http\Request $request
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function login(Request $request): JsonResponse
  {
    if ($this->hasTooManyLoginAttempts($request)) {
      $this->fireLockoutEvent($request);

      return $this->sendLockoutResponse($request);
    }

    $credentials = $request->only('email', 'password');

    try {
      $token = auth('api')->attempt($credentials);

      if (!$token) {
        $this->incrementLoginAttempts($request);

        return response()->json([
          'error' => [
            'status'  => Response::HTTP_UNAUTHORIZED,
            'message' => trans('auth.failed'),
          ],
        ], Response::HTTP_UNAUTHORIZED);
      }
    } catch (JWTException $exception) {
      $this->incrementLoginAttempts($request);

      return response()->json([
        'error' => [
          'status'  => Response::HTTP_INTERNAL_SERVER_ERROR,
          'message' => $exception->getMessage(),
        ],
      ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    return response()->json([
      'data' => [
        'access_token' => $token,
        'token_type'   => 'Bearer',
        'expires_in'   => auth('api')->factory()->getTTL() * 60,
      ],
    ], Response::HTTP_CREATED);
  }

  /**
   * Get the login username to be used by the controller
   *
   * @return string
   */
  public function username(): string
  {
    return 'email';
  }

  /**
   * Redirect the user after determining they are locked out
   *
   * @param \Illuminate\Http\Request $request
   *
   * @return \Illuminate\Http\JsonResponse
   */
  protected function sendLockoutResponse(Request $request): JsonResponse
  {
    $seconds = $this->limiter()->availableIn(
      $this->throttleKey($request)
    );

    return response()->json([
      'error' => [
        'status'  => Response::HTTP_TOO_MANY_REQUESTS,
        'message' => trans('auth.throttle', ['seconds' => $seconds]),
      ],
    ], Response::HTTP_TOO_MANY_REQUESTS);
  }
}
