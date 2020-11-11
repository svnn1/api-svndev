<?php

namespace App\Support\Testing;

use Illuminate\Contracts\Console\Kernel;

/**
 * Trait DatabaseMigrations
 *
 * @package App\Support\Testing
 */
trait DatabaseMigrations
{
  /**
   * Define hooks to migrate the database before and after each test.
   *
   * @return void
   */
  public function runDatabaseMigrations()
  {
    $this->artisan('migrator');

    $this->app[Kernel::class]->setArtisan(NULL);

    $this->beforeApplicationDestroyed(fn() => $this->artisan('migrator:rollback'));
  }
}
