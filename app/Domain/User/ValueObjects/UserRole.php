<?php

namespace App\Domain\User\ValueObjects;

enum UserRole: string
{
    case CLIENT = 'client';
    case FREELANCER = 'freelancer';
    case ADMIN = 'admin';
    case SUPER_ADMIN = 'super_admin';

    public function canPost(): bool
    {
        return in_array($this, [self::CLIENT, self::ADMIN]);
    }

    public function canBid(): bool
    {
        return in_array($this, [self::FREELANCER, self::ADMIN]);
    }
}
