<?php

namespace App\Units\Core\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;

/**
 * Class RedirectIfAuthenticated
 *
 * @package App\Units\Core\Http\Middleware
 */
class RedirectIfAuthenticated
{
  /**
   * Handle an incoming request.
   *
   * @param \Illuminate\Http\Request $request
   * @param \Closure                 $next
   * @param string|null              $guard
   *
   * @return mixed
   */
  public function handle($request, Closure $next, $guard = NULL)
  {
    if (Auth::guard($guard)->check()) {
      return redirect(RouteServiceProvider::HOME);
    }

    return $next($request);
  }
}
