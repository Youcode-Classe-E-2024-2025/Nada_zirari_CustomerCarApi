<?php

namespace App\Services;

use App\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    /**
     * Register a new user
     *
     * @param array $userData
     * @return array
     */
    public function register(array $userData): array
    {
        // Hash the password
        $userData['password'] = Hash::make($userData['password']);
        
        // Create the user
        $user = $this->userRepository->create($userData);
        
        // Generate token
        $token = $user->createToken('auth_token')->plainTextToken;
        
        return [
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ];
    }

    /**
     * Login a user
     *
     * @param array $credentials
     * @return array
     * @throws ValidationException
     */
    public function login(array $credentials): array
    {
        $user = $this->userRepository->findByEmail($credentials['email']);

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['The provided credentials are incorrect.'],
            ]);
        }

        $deviceName = $credentials['device_name'] ?? $credentials['user_agent'] ?? 'Unknown Device';
        $token = $user->createToken($deviceName)->plainTextToken;

        return [
            'user' => $user,
            'access_token' => $token,
            'token_type' => 'Bearer',
        ];
    }

    /**
     * Logout a user
     *
     * @param object $user
     * @return bool
     */
    public function logout(object $user): bool
    {
        return $user->currentAccessToken()->delete();
    }
}
