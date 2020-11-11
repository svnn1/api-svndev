<?php

namespace App\Support\Unit;

use ReflectionClass;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

/**
 * Class ServiceProvider
 *
 * @package App\Support\Unit
 */
abstract class ServiceProvider extends LaravelServiceProvider
{
  /**
   * Alias for translations and views
   *
   * @var string
   */
  protected string $alias;

  /**
   * List of Unit Service Providers to Register.
   *
   * @var array
   */
  protected array $providers = [];

  /**
   * Enable views loading on the unit.
   *
   * @var bool
   */
  protected bool $hasViews = FALSE;

  /**
   * Enable translations loading on the unit.
   *
   * @var bool
   */
  protected bool $hasTranslations = FALSE;

  /**
   * Boot required registering of views and translations.
   *
   * @return void
   * @throws \ReflectionException
   */
  public function boot(): void
  {
    $this->registerViews();
    $this->registerTranslations();
  }

  /**
   * Register a service provider with the application.
   *
   * @return void
   */
  public function register(): void
  {
    $this->registerProviders(collect($this->providers));
  }

  /**
   * Register providers.
   *
   * @param \Illuminate\Support\Collection $providers
   *
   * @return void
   */
  protected function registerProviders(Collection $providers): void
  {
    $providers->each(fn($provider) => $this->app->register($provider));
  }

  /**
   * Register translations.
   *
   * @return void
   * @throws \ReflectionException
   */
  protected function registerTranslations(): void
  {
    if ($this->hasTranslations) {
      $this->loadTranslationsFrom(
        $this->unitPath('Lang'),
        $this->alias
      );
    }
  }

  /**
   * Register views.
   *
   * @return void
   * @throws \ReflectionException
   */
  protected function registerViews(): void
  {
    if ($this->hasViews) {
      $this->loadViewsFrom(
        $this->unitPath('Views'),
        $this->alias
      );
    }
  }

  /**
   * Detects the unit base path so resources can be proper loaded
   * on child classes.
   *
   * @param string|null $append
   *
   * @return string
   * @throws \ReflectionException
   */
  protected function unitPath(?string $append = NULL): string
  {
    $reflection = new ReflectionClass($this);
    $realPath   = realpath(dirname($reflection->getFileName()) . '/../');

    if (!$append) {
      return $realPath;
    }

    return "{$realPath}/{$append}";
  }
}
