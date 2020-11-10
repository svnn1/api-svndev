<?php

namespace App\Units\Auth\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;

/**
 * Class AuthServiceProvider
 *
 * @package App\Units\Auth\Providers
 */
class AuthServiceProvider extends ServiceProvider
{
  /**
   * The policy mappings for the application.
   *
   * @var array
   */
  protected $policies = [];

  /**
   * Register any authentication / authorization services.
   *
   * @return void
   */
  public function boot()
  {
    $this->registerPolicies();
  }
}
