<?php

namespace Zus1\LaravelAuth\Repository;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Symfony\Component\HttpKernel\Exception\HttpException;

class UserRepository
{
    private static $_model = null;

    public function __construct()
    {
        self::$_model = (string) config('laravel-auth.user_class');
    }

    public function findByEmailOr404(string $email): Model
    {
        $user = $this->findByEmail($email);

        if($user === null) {
            throw new HttpException(404, 'User not found');
        }

        return $user;
    }

    private function findByEmail(string $email): ?Model
    {
        $builder = $this->getBuilder();

        $builder->where('email', $email);

        return $builder->first();
    }

    private function getBuilder(): Builder
    {
        /** @var Model $model */
        $model = new (self::$_model);

        return $model->newModelQuery();

    }
}