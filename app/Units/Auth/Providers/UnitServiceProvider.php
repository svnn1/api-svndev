<?php

namespace App\Units\Auth\Providers;

use App\Support\Unit\ServiceProvider;

/**
 * Class UnitServiceProvider
 *
 * @package App\Units\Auth\Providers
 */
class UnitServiceProvider extends ServiceProvider
{
  /**
   * Alias for translations and views
   *
   * @var string
   */
  protected string $alias = 'unit:auth';

  /**
   * List of Unit Service Providers to Register.
   *
   * @var array
   */
  protected array $providers = [
    AuthServiceProvider::class,
    RouteServiceProvider::class,
  ];
}
