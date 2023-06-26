<?php

namespace Shops\Application\Policies;

use Accounts\Domain\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ShopPolicy
{
    use HandlesAuthorization;

    /**
     * Может ли пользователь просматривать список магазинов
     */
    public function viewAny(User $authUser): bool
    {
        // Пока что могут только админы
        return ($authUser->role === 'admin');
    }

    /**
     * Может ли пользователь просматривать магазин
     */
    public function view(User $authUser): bool
    {
        // Может, если админ, или просматривает себя
        return ($authUser->role === 'admin');
    }

    /**
     * Может ли пользователь создавать другие магазины
     */
    public function create(User $authUser): bool
    {
        // Пока что могут только админы
        return ($authUser->role === 'admin');
    }

    /**
     * Может ли пользователь редактировать магаизны
     */
    public function update(User $authUser): bool
    {
        // Пока что могут только админы
        return ($authUser->role === 'admin');
    }

    /**
     * Может ли пользователь удалять магазин
     */
    public function delete(User $authUser): bool
    {
        // Пока что могут только админы
        return ($authUser->role === 'admin');
    }
}