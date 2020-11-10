<?php

namespace App\Units;

use Throwable;
use Illuminate\Http\Response;
use Illuminate\Database\QueryException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler;
use Illuminate\Validation\ValidationException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;

/**
 * Class ExceptionHandler
 *
 * @package App\Units
 */
class ExceptionHandler extends Handler
{
  /**
   * A list of the exception types that are not reported.
   *
   * @var array
   */
  protected $dontReport = [];

  /**
   * A list of the inputs that are never flashed for validation exceptions.
   *
   * @var array
   */
  protected $dontFlash = [
    'password', 'password_confirmation',
  ];

  /**
   * Render an exception into an HTTP response
   *
   * @param \Illuminate\Http\Request $request
   * @param \Throwable               $exception
   *
   * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response
   * @throws \Throwable
   */
  public function render($request, Throwable $exception)
  {
    if ($exception instanceof ValidationException) {
      return $this->convertValidationExceptionToResponse($exception, $request);
    }

    if ($exception instanceof ModelNotFoundException) {
      $modelName = strtolower(class_basename($exception->getModel()));

      return response()->json([
        'error' => [
          'status'  => Response::HTTP_NOT_FOUND,
          'message' => "Does not exists any $modelName with the specified identifier.",
        ],
      ], Response::HTTP_NOT_FOUND);
    }

    if ($exception instanceof AuthenticationException) {
      return response()->json([
        'error' => [
          'status'  => Response::HTTP_UNAUTHORIZED,
          'message' => $exception->getMessage(),
        ],
      ], Response::HTTP_UNAUTHORIZED);
    }

    if ($exception instanceof AuthorizationException) {
      return response()->json([
        'error' => [
          'status'  => Response::HTTP_FORBIDDEN,
          'message' => $exception->getMessage(),
        ],
      ], Response::HTTP_FORBIDDEN);
    }

    if ($exception instanceof NotFoundHttpException) {
      return response()->json([
        'error' => [
          'status'  => Response::HTTP_NOT_FOUND,
          'message' => 'The specified URL cannot be found.',
        ],
      ], Response::HTTP_NOT_FOUND);
    }

    if ($exception instanceof MethodNotAllowedHttpException) {
      return response()->json([
        'error' => [
          'status'  => Response::HTTP_METHOD_NOT_ALLOWED,
          'message' => 'The specified method for the requests is invalid.',
        ],
      ], Response::HTTP_METHOD_NOT_ALLOWED);
    }

    if ($exception instanceof HttpException) {
      return response()->json([
        'error' => [
          'status'  => $exception->getStatusCode(),
          'message' => $exception->getMessage(),
        ],
      ], $exception->getStatusCode());
    }

    if ($exception instanceof QueryException) {
      $errorCode = $exception->errorInfo[1];

      if ($errorCode === 1451) {
        return response()->json([
          'error' => [
            'status'  => Response::HTTP_CONFLICT,
            'message' => 'Cannot remove this resource permanently. It is related with any other resource.',
          ],
        ], Response::HTTP_CONFLICT);
      }

      return response()->json([
        'error' => [
          'status'  => Response::HTTP_CONFLICT,
          'message' => $exception->errorInfo[2],
        ],
      ], Response::HTTP_CONFLICT);
    }

    if (config('app.debug')) {
      return parent::render($request, $exception);
    }

    return response()->json([
      'error' => [
        'status'  => Response::HTTP_INTERNAL_SERVER_ERROR,
        'message' => 'Unexpected exception. Try later.',
      ],
    ], Response::HTTP_INTERNAL_SERVER_ERROR);
  }

  /**
   * Create a response object from the given validation exception
   *
   * @param \Illuminate\Validation\ValidationException $exception
   * @param \Illuminate\Http\Request                   $request
   *
   * @return \Illuminate\Http\JsonResponse|\Illuminate\Http\Response|\Symfony\Component\HttpFoundation\Response|null
   */
  protected function convertValidationExceptionToResponse(ValidationException $exception, $request)
  {
    if ($exception->response) {
      return $exception->response;
    }

    return response()->json([
      'error' => [
        'status'  => $exception->status,
        'message' => 'The given data was invalid.',
        'errors'  => $exception->errors(),
      ],
    ], $exception->status);
  }
}
