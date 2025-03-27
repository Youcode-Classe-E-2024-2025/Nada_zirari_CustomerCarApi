<?php

namespace App\Repositories;

use App\Models\User;

class UserRepository
{
    protected $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    /**
     * Create a new user
     *
     * @param array $userData
     * @return User
     */
    public function create(array $userData): User
    {
        return $this->model->create($userData);
    }

    /**
     * Find a user by email
     *
     * @param string $email
     * @return User|null
     */
    public function findByEmail(string $email): ?User
    {
        return $this->model->where('email', $email)->first();
    }

    /**
     * Check if email is unique
     *
     * @param string $email
     * @return bool
     */
    public function isEmailUnique(string $email): bool
    {
        return $this->model->where('email', $email)->doesntExist();
    }
}
