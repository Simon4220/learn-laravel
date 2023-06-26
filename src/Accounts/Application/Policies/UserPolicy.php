<?php

namespace Accounts\Application\Policies;

use Accounts\Contracts\DataTransferObjects\UserDto;
use Accounts\Domain\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Может ли пользователь просматривать список пользователей
     */
    public function viewAny(User $authUser): bool
    {
        // Пока что могут только админы
        return ($authUser->role === 'admin');
    }

    /**
     * Может ли пользователь просматривать другого пользователя
     */
    public function view(User $authUser, User|UserDto $user): bool
    {
        // Может, если админ, или просматривает себя
        return ($authUser->role === 'admin' or $authUser->id === $user->id);
    }

    /**
     * Может ли пользователь создавать других пользователей
     */
    public function create(User $authUser): bool
    {
        // Пока что могут только админы
        return ($authUser->role === 'admin');
    }

    /**
     * Может ли пользователь редактировать другого пользователя в целом
     */
    public function update(User $authUser, User|UserDto $user): bool
    {
        // Пока что могут только админы
        return ($authUser->role === 'admin');
    }

    /**
     * Может ли пользователь удалять другого пользователя
     */
    public function delete(User $authUser, User|UserDto $user): bool
    {
        // Пока что могут только админы
        return ($authUser->role === 'admin' and $authUser->id !== $user->id);
    }
}
