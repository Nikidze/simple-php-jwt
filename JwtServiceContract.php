<?php

interface JwtServiceContract
{
    public function generate(int $user_id): string;

    public function validate(string $token): bool;

    public function getUserId(string $token): int;
}
