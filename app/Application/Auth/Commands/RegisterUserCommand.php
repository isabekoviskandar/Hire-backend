<?php

namespace App\Application\Auth\Commands;

class RegisterUserCommand
{
    public function __construct(
        public readonly string $first_name,
        public readonly string $last_name,
        public readonly string $email,
        public readonly string $password,
        public readonly string $role = 'client',
    ) {}
}
