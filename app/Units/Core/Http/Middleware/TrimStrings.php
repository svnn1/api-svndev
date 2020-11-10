<?php

namespace App\Units\Core\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\TrimStrings as Middleware;

/**
 * Class TrimStrings
 *
 * @package App\Units\Core\Http\Middleware
 */
class TrimStrings extends Middleware
{
  /**
   * The names of the attributes that should not be trimmed.
   *
   * @var array
   */
  protected $except = [
    'password', 'password_confirmation',
  ];
}
