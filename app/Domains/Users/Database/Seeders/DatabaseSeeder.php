<?php

namespace App\Domains\Users\Database\Seeders;

use Illuminate\Database\Seeder;

/**
 * Class DatabaseSeeder
 *
 * @package App\Domains\Users\Database\Seeders
 */
class DatabaseSeeder extends Seeder
{
  /**
   * Seed the application's database.
   *
   * @return void
   */
  public function run(): void
  {
    $this->call(UserSeeder::class);
  }
}
