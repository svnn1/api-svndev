<?php

namespace App\Units\Auth\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Support\Http\Controllers\Controller;

/**
 * Class LogoutController
 *
 * @package App\Units\Auth\Http\Controllers
 */
class LogoutController extends Controller
{
  /**
   * Log the user out of the application.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function logout(): JsonResponse
  {
    Auth::guard('api')->logout();

    return response()->json([
      'data' => [
        'message' => 'You have successfully logged out.',
      ],
    ], Response::HTTP_OK);
  }
}
