<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Social extends Model
{
    protected $fillable =
        [
            'user_id',
            'linkedin',
            'github',
            'telegram',
            'twitter',
            'facebook',
            'instagam',
            'website',
            'additional_info',
        ];
}
