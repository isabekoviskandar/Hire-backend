<?php

namespace App\Application\Auth\Handlers;

use App\Application\Auth\Commands\LoginUserCommand;
use App\Domain\User\Entities\User;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Domain\User\ValueObjects\Email;
use Illuminate\Support\Facades\Hash;

class LoginUserHandler
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
    ) {}

    public function handle(LoginUserCommand $command): User
    {
        $email = new Email($command->email);
        $user = $this->userRepository->findByEmail($email);

        if (! $user || ! Hash::check($command->password, $user->password())) {
            throw new \DomainException('Invalid credentials');
        }

        return $user;
    }
}
