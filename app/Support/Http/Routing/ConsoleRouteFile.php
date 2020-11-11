<?php

namespace App\Support\Http\Routing;

use Illuminate\Contracts\Console\Kernel;

/**
 * Class ConsoleRouteFile
 *
 * @package App\Support\Http\Routing
 */
abstract class ConsoleRouteFile
{
  /**
   * @var \Illuminate\Contracts\Console\Kernel
   */
  protected Kernel $artisan;

  /**
   * @var \Illuminate\Contracts\Console\Kernel
   */
  protected Kernel $router;

  /**
   * ConsoleRouteFile constructor.
   */
  public function __construct()
  {
    $this->artisan = app(Kernel::class);
    $this->router  = $this->artisan;
  }

  /**
   * Register Console routes
   *
   * @return void
   */
  public function register(): void
  {
    $this->routes();
  }

  /**
   * Declare Console routes
   *
   * @return void
   */
  abstract public function routes(): void;
}
