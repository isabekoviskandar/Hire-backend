<?php

namespace App\Domain\User\Repositories;

use App\Domain\User\Entities\User as UserEntity;
use App\Domain\User\ValueObjects\Email;

interface UserRepositoryInterface
{
    public function findById(int $id): ?UserEntity;

    public function findByEmail(Email $email): ?UserEntity;

    public function save(UserEntity $user): UserEntity;

    public function update(UserEntity $user): UserEntity;

    public function delete(int $id): void;

    public function existsByEmail(Email $email): bool;
}
