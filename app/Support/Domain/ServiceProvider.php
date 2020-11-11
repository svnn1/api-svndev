<?php

namespace App\Support\Domain;

use ReflectionClass;
use Migrator\MigratorTrait;
use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;

/**
 * Class ServiceProvider
 *
 * @package App\Support\Domain
 */
abstract class ServiceProvider extends LaravelServiceProvider
{
  use MigratorTrait;

  /**
   * Domains alias for translations and other keys.
   *
   * @var string
   */
  protected string $alias;

  /**
   * List of domain providers to register.
   *
   * @var array
   */
  protected array $subProviders = [];

  /**
   * List of migrations provided by domain.
   *
   * @var array
   */
  protected array $migrations = [];

  /**
   * List of seeders provided by domain.
   *
   * @var array
   */
  protected array $seeders = [];

  /**
   * List of model factories to load.
   *
   * @var array
   */
  protected array $factories = [];

  /**
   * Enable views loading on the domain.
   *
   * @var bool
   */
  protected bool $hasViews = FALSE;

  /**
   * Enable translations for this domain.
   *
   * @var bool
   */
  protected bool $hasTranslations = FALSE;

  /**
   * Register a bindings.
   *
   * @var array
   */
  public array $bindings = [];

  /**
   * Boot the application's service providers.
   *
   * @return void
   * @throws \ReflectionException
   */
  public function boot(): void
  {
    $this->registerTranslations();
    $this->registerViews();
  }

  /**
   * Register the current domain.
   *
   * @return void
   */
  public function register(): void
  {
    $this->registerSubProviders(collect($this->subProviders));
    $this->registerBindings(collect($this->bindings));
    $this->registerMigrations(collect($this->migrations));
    $this->registerSeeders(collect($this->seeders));
    $this->registerFactories(collect($this->factories));
  }

  /**
   * Register sub providers.
   *
   * @param \Illuminate\Support\Collection $subProviders
   *
   * @return void
   */
  protected function registerSubProviders(Collection $subProviders): void
  {
    $subProviders->each(fn($provider) => $this->app->register($provider));
  }

  /**
   * Register bindings.
   *
   * @param \Illuminate\Support\Collection $bindings
   *
   * @return void
   */
  protected function registerBindings(Collection $bindings): void
  {
    $bindings->each(fn($concretion, $abstraction) => $this->app->bind($abstraction, $concretion));
  }

  /**
   * Register migrations.
   *
   * @param \Illuminate\Support\Collection $migrations
   *
   * @return void
   */
  protected function registerMigrations(Collection $migrations): void
  {
    $this->migrations($migrations->all());
  }

  /**
   * Register seeders.
   *
   * @param \Illuminate\Support\Collection $seeders
   *
   * @return void
   */
  protected function registerSeeders(Collection $seeders): void
  {
    $this->seeders($seeders->all());
  }

  /**
   * Register factories.
   *
   * @param \Illuminate\Support\Collection $factories
   *
   * @return void
   */
  protected function registerFactories(Collection $factories): void
  {
    $factories->each(fn($factory) => (new $factory())->define());
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
        $this->domainPath('Lang'),
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
        $this->domainPath('Views'),
        $this->alias
      );
    }
  }

  /**
   * Get domain path.
   *
   * @param string|null $append
   *
   * @return string
   * @throws \ReflectionException
   */
  protected function domainPath(?string $append = NULL): string
  {
    $reflection = new ReflectionClass($this);
    $realPath   = realpath(dirname($reflection->getFileName()) . '/../');

    if (!$append) {
      return $realPath;
    }

    return "{$realPath}/{$append}";
  }
}
