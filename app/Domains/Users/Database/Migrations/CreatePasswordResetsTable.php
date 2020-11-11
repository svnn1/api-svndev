<?php


namespace App\Domains\Users\Database\Migrations;

use App\Support\Domain\Migration;
use Illuminate\Database\Schema\Blueprint;

/**
 * Class CreatePasswordResetsTable
 *
 * @package App\Domains\Users\Database\Migrations
 */
class CreatePasswordResetsTable extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up(): void
  {
    $this->schema->create('password_resets', function (Blueprint $table) {
      $table->string('email')->index();
      $table->string('token');
      $table->timestamp('created_at')->nullable();
    });
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down(): void
  {
    $this->schema->dropIfExists('password_resets');
  }
}
