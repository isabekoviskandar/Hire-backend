<?php

namespace App\Infrastructure\Persistence;

use App\Domain\User\Entities\User as UserEntity;
use App\Domain\User\Repositories\UserRepositoryInterface;
use App\Domain\User\ValueObjects\Email;
use App\Domain\User\ValueObjects\UserRole;
use App\Models\User as EloquentUser;

class EloquentUserRepository implements UserRepositoryInterface
{
    public function findById(int $id): ?UserEntity
    {
        $record = EloquentUser::find($id);

        return $record ? $this->toEntity($record) : null;
    }

    public function findByEmail(Email $email): ?UserEntity
    {
        $record = EloquentUser::where('email', $email->value())->first();

        return $record ? $this->toEntity($record) : null;
    }

    public function existsByEmail(Email $email): bool
    {
        return EloquentUser::where('email', $email->value())->exists();
    }

    public function save(UserEntity $user): UserEntity
    {
        $record = EloquentUser::updateOrCreate(
            ['id' => $user->id() ?: null],
            [
                'first_name' => $user->firstName(),
                'last_name' => $user->lastName(),
                'email' => $user->email()->value(),
                'password' => $user->password(),
                'role' => $user->role()->value,
                'is_verified' => $user->isVerified(),
            ]
        );

        return $this->toEntity($record);
    }

    public function update(UserEntity $user): UserEntity
    {
        return $this->save($user);
    }

    private function toEntity(EloquentUser $record): UserEntity
    {
        return new UserEntity(
            id: $record->id,
            first_name: $record->first_name,
            last_name: $record->last_name,
            middle_name: $record->middle_name ?? '',
            date_of_birth: $record->date_of_birth ?? '',
            gender: $record->gender ?? '',
            role: UserRole::from($record->role),
            bio: $record->bio ?? '',
            profile_photo: $record->profile_photo ?? '',
            cover_photo: $record->cover_photo ?? '',
            resume: $record->resume ?? '',
            is_verified: (bool) $record->is_verified,
            email: new Email($record->email),
            password: $record->password,
        );
    }

    public function delete(int $id): void
    {
        EloquentUser::destroy($id);
    }
}
