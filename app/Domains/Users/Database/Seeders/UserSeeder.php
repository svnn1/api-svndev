<?php

namespace App\Domains\Users\Database\Seeders;

use Illuminate\Database\Seeder;
use App\Domains\Users\Models\User;

/**
 * Class UserSeeder
 *
 * @package App\Domains\Users\Database\Seeders
 */
class UserSeeder extends Seeder
{
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run(): void
  {
    factory(User::class)->create();
  }
}
