<?php

namespace App\Units\Auth\Http\Controllers;

use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use App\Support\Http\Controllers\Controller;

/**
 * Class RefreshTokenController
 *
 * @package App\Units\Auth\Http\Controllers
 */
class RefreshTokenController extends Controller
{
  /**
   * Refresh token.
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function refresh(): JsonResponse
  {
    $token = Auth::guard('api')->refresh();

    return response()->json([
      'data' => [
        'access_token' => $token,
        'token_type'   => 'Bearer',
        'expires_in'   => Auth::guard('api')->factory()->getTTL() * 60,
      ],
    ], Response::HTTP_CREATED);
  }
}
