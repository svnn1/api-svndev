<?php

namespace App\Units\Auth\Providers;

use App\Units\Auth\Http\Routes\Api;
use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;

/**
 * Class RouteServiceProvider
 *
 * @package App\Units\Auth\Providers
 */
class RouteServiceProvider extends ServiceProvider
{
  protected $namespace = 'App\Units\Authentication\Http\Controllers';

  /**
   * Define the routes for the application.
   *
   * @return void
   */
  public function map(): void
  {
    $this->mapApiRoutes();
  }

  /**
   * Define routes for this unit.
   *
   * @return void
   */
  protected function mapApiRoutes(): void
  {
    (new Api([
      'middleware' => 'api',
      'namespace'  => $this->namespace,
    ]))->register();
  }
}
