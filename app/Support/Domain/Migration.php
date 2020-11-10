<?php

namespace App\Support\Domain;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Builder;
use Illuminate\Database\Migrations\Migration as LaravelMigration;

/**
 * Class Migration
 *
 * @package App\Support\Domain
 */
abstract class Migration extends LaravelMigration
{
  /**
   * @var \Illuminate\Database\Schema\Builder
   */
  protected Builder $schema;

  /**
   * Migration constructor
   */
  public function __construct()
  {
    $this->schema = DB::connection()->getSchemaBuilder();
  }

  /**
   * Run the migrations
   *
   * @return void
   */
  abstract public function up(): void;

  /**
   * Reverse the migrations
   *
   * @return void
   */
  abstract public function down(): void;
}
