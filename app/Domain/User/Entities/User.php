<?php

namespace App\Domain\User\Entities;

use App\Domain\User\ValueObjects\Email;
use App\Domain\User\ValueObjects\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    public function __construct(
        private readonly int $id,
        private string $first_name,
        private string $last_name,
        private string $middle_name,
        private string $date_of_birth,
        private string $gender,
        private UserRole $role,
        private string $bio,
        private string $profile_photo,
        private string $cover_photo,
        private string $resume,
        private Email $email,
        private bool $is_verified,
        private string $password,
    ) {}

    use HasApiTokens, HasFactory , Notifiable;

    public function canPostJobs(): bool
    {
        return $this->role->canPost();
    }

    public function canBidOnJobs(): bool
    {
        return $this->role->canBid();
    }

    public function verify(): void
    {
        $this->is_verified = true;
    }

    public function switchRole(UserRole $newRole): void
    {
        $this->role = $newRole;
    }

    public function id(): int
    {
        return $this->id;
    }

    public function firstName(): string
    {
        return $this->first_name;
    }

    public function lastName(): string
    {
        return $this->last_name;
    }

    public function email(): Email
    {
        return $this->email;
    }

    public function password(): string
    {
        return $this->password;
    }

    public function role(): UserRole
    {
        return $this->role;
    }

    public function isVerified(): bool
    {
        return $this->is_verified;
    }

    public function middleName(): string
    {
        return $this->middle_name;
    }

    public function dateOfBirth(): string
    {
        return $this->date_of_birth;
    }

    public function gender(): string
    {
        return $this->gender;
    }

    public function bio(): string
    {
        return $this->bio;
    }

    public function profilePhoto(): string
    {
        return $this->profile_photo;
    }

    public function coverPhoto(): string
    {
        return $this->cover_photo;
    }

    public function resume(): string
    {
        return $this->resume;
    }
}
