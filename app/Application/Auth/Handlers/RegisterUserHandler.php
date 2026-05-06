<?php

namespace App\Application\Auth\Handlers;

use App\Application\Auth\Commands\RegisterUserCommand;
use App\Domain\User\Entities\User;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Domain\User\ValueObjects\Email;
use App\Domain\User\ValueObjects\UserRole;
use Illuminate\Support\Facades\Hash;

class RegisterUserHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
    ) {}

    public function handle(RegisterUserCommand $command): User
    {
        $email = new Email($command->email);

        if ($this->userRepository->existsByEmail($email)) {
            throw new \DomainException('Email already taken');
        }

        $user = new User(
            id: 0, // DB will assign
            first_name: $command->first_name,
            last_name: $command->last_name,
            middle_name: '',
            date_of_birth: '',
            gender: '',
            bio: '',
            profile_photo: '',
            cover_photo: '',
            resume: '',
            is_verified: false,
            email: $email,
            password: Hash::make($command->password),
            role: UserRole::from($command->role),
        );

        return $this->userRepository->save($user);
    }
}
