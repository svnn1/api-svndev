<?php

namespace App\Domains\Users\Repositories;

use App\Domains\Users\Models\User;
use App\Support\Repositories\BaseRepository;
use App\Domains\Users\Contracts\Repositories as Contracts;

/**
 * Class UserRepository
 *
 * @package App\Domains\Users\Repositories
 */
class UserRepository extends BaseRepository implements Contracts\UserRepository
{
  /**
   * Model class for repository.
   *
   * @var string
   */
  protected string $model = User::class;
}
