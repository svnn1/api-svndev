<?php

namespace App\Units\Auth\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Auth\Events\Verified;
use App\Support\Http\Controllers\Controller;
use Illuminate\Auth\Access\AuthorizationException;

/**
 * Class VerificationController
 *
 * @package App\Units\Auth\Http\Controllers
 */
class VerificationController extends Controller
{
  /**
   * VerificationController constructor.
   */
  public function __construct()
  {
    $this->middleware('auth');
    $this->middleware('signed')->only('verify');
    $this->middleware('throttle:6,1')->only('verify', 'resend');
  }

  /**
   * Mark the authenticated user's email address as verified.
   *
   * @param \Illuminate\Http\Request $request
   *
   * @return \Illuminate\Http\JsonResponse
   * @throws \Illuminate\Auth\Access\AuthorizationException
   */
  public function verify(Request $request): JsonResponse
  {
    if ($request->route('id') != $request->user()->getKey()) {
      throw new AuthorizationException;
    }

    if ($request->user()->hasVerifiedEmail()) {
      return response()->json([
        'data' => [
          'message' => 'Your email is already verified.',
        ],
      ], Response::HTTP_OK);
    }

    if ($request->user()->markEmailAsVerified()) {
      event(new Verified($request->user()));
    }

    return response()->json([
      'data' => [
        'message' => 'Your email has been successfully verified.',
      ],
    ], Response::HTTP_OK);
  }

  /**
   * Resend the email verification notification.
   *
   * @param \Illuminate\Http\Request $request
   *
   * @return \Illuminate\Http\JsonResponse
   */
  public function resend(Request $request): JsonResponse
  {
    if ($request->user()->hasVerifiedEmail()) {
      return response()->json([
        'data' => [
          'message' => 'Your email is already verified.',
        ],
      ], Response::HTTP_OK);
    }

    $request->user()->sendEmailVerificationNotification();

    return response()->json([
      'data' => [
        'message' => 'Verification email sent successfully.',
      ],
    ], Response::HTTP_OK);
  }
}
