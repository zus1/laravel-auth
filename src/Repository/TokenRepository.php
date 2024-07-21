<?php

namespace Zus1\LaravelAuth\Repository;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\JoinClause;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Zus1\LaravelAuth\Constant\Token\TokenType;
use Zus1\LaravelAuth\Helper\TokenHelper;
use Zus1\LaravelAuth\Models\Token;

class TokenRepository
{
    protected const MODEL = Token::class;

    public function __construct(
        private TokenHelper $tokenHelper,
    ){
    }

    public function create(Model $user, string $type): Token
    {
        /** @var TokenType $tokenTypeClass */
        $tokenTypeClass = (string) config('laravel-auth.token.type_class');
        $action = $tokenTypeClass::action($type);

        $token = new Token();
        $token->token = $this->tokenHelper->$action($tokenTypeClass::length($type));
        $token->created_at = Carbon::now()->format('Y-m-d  H:i:s');
        $token->expires_at = $tokenTypeClass::expiresAt($type, Carbon::now());
        $token->type = $type;

        if(method_exists($user, 'tokens')) {
            $user->tokens()->save($token);
        }

        return $token;
    }

    public function retrieve(string $tokenString, bool $expired = false): Token
    {
        $expiresAtOperator = $expired === true ? '<=' : '>';

        $builder = $this->getBuilder();

        /** @var Token $token */
        $token = $builder->where('token', $tokenString)
            ->where('active', 1)
            ->where('expires_at', $expiresAtOperator, Carbon::now()->format('Y-m-d H:i:s'))
            ->first();

        if($token === null) {
            throw new HttpException(404, 'Token not found');
        }

        return $token;
    }

    public function deactivate(Token $token): Token
    {
        $token->active = false;
        $token->save();

        return $token;
    }

    public function isExpired(string $token): bool
    {
        $builder = $this->getBuilder();

        /** @var Token $token $token */
        $token = $builder->where('token', $token)
            ->where('expires_at', '<=', Carbon::now()->format('Y-m-d H:i:s'))
            ->first();

        if($token === null) {
            return false;
        }

        if($token->active === false) {
            throw new HttpException(400, 'Token is inactive');
        }

        return true;
    }

    private function getBuilder(): Builder
    {
        /** @var Model $model */
        $model = new (self::MODEL);

        return $model->newModelQuery();

    }

    public function invalidateAllUserAuthenticationTokens(string $email): int
    {
        $builder = $this->getBuilder();

        return $builder->join('users', function (JoinClause $join) {
            $join->on('users.id', 'tokens.user_id');
        })->where('tokens.active', true)
            ->where('users.email', $email)
            ->where('tokens.expires_at', '>', Carbon::now()->format('Y-m-d H:i:s'))
            ->where(function (Builder $builder) {
            $builder->where('tokens.type', TokenType::ACCESS)
                ->orWhere('tokens.type', TokenType::REFRESH);
        })->update(['tokens.active' => false]);
    }
}
