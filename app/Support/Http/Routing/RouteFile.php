<?php

namespace App\Support\Http\Routing;

use Illuminate\Routing\Router;

/**
 * Class RouteFile
 *
 * @package App\Support\Http\Routing
 */
abstract class RouteFile
{
  /**
   * @var \Illuminate\Routing\Router
   */
  protected $router;

  /**
   * @var array
   */
  protected array $options;

  /**
   * RouteFile constructor.
   *
   * @param array $options
   */
  public function __construct(array $options)
  {
    $this->router  = app(Router::class);
    $this->options = $options;
  }

  /**
   * Register routes.
   *
   * @return void
   */
  public function register(): void
  {
    $this->router->group($this->options, fn() => $this->routes());
  }

  /**
   * Define routes.
   *
   * @return void
   */
  abstract public function routes(): void;
}
