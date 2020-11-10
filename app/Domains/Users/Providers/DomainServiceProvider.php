<?php

namespace App\Domains\Users\Providers;

use App\Support\Domain\ServiceProvider;
use App\Domains\Users\Repositories as Repositories;
use App\Domains\Users\Database\Factories\UserFactory;
use App\Domains\Users\Database\Seeders\DatabaseSeeder;
use App\Domains\Users\Contracts\Repositories as Contracts;
use App\Domains\Users\Database\Migrations\CreateUsersTable;
use App\Domains\Users\Database\Migrations\CreateFailedJobsTable;
use App\Domains\Users\Database\Migrations\CreatePasswordResetsTable;

/**
 * Class DomainServiceProvider
 *
 * @package App\Domains\Users\Providers
 */
class DomainServiceProvider extends ServiceProvider
{
  /**
   * Domains alias for translations and other keys
   *
   * @var string
   */
  protected string $alias = 'domain:users';

  /**
   * List providers provided by domain
   *
   * @var array
   */
  protected array $subProviders = [
    EventServiceProvider::class,
  ];

  /**
   * List bindings provided by domain
   *
   * @var array
   */
  public array $bindings = [
    Contracts\UserRepository::class => Repositories\UserRepository::class,
  ];

  /**
   * List of migrations provided by domain
   *
   * @var array
   */
  protected array $migrations = [
    CreatePasswordResetsTable::class,
    CreateUsersTable::class,
    CreateFailedJobsTable::class,
  ];

  /**
   * List of seeders provided by domain
   *
   * @var array
   */
  protected array $seeders = [
    DatabaseSeeder::class,
  ];

  /**
   * List of model factories to load.
   *
   * @var array
   */
  protected array $factories = [
    UserFactory::class,
  ];
}
