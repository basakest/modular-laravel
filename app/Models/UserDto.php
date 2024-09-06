<?php

namespace App\Models;

readonly class UserDto
{
    public function __construct(
        public int $id,
        public string $email,
        public string $name,
    )
    {
    }

    public static function fromEloquentModel(User $user): UserDto
    {
        return new self($user->id, $user->email, $user->name);
    }
}
