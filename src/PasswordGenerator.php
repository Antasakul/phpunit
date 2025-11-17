<?php

namespace App;

class PasswordGenerator
{
    public function generate(int $length = 12, bool $useSpecialChars = false): string
    {
        if ($length < 6) {
            throw new \InvalidArgumentException("Длина пароля не может быть меньше 6 символов");
        }

        $lowercase = 'abcdefghijklmnopqrstuvwxyz';
        $uppercase = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $numbers = '0123456789';
        $specialChars = '!@#$%^&*()_+-=[]{}|;:,.<>?';

        $characterPool = $lowercase . $uppercase . $numbers;

        if ($useSpecialChars) {
            $characterPool .= $specialChars;
        }

        $password = '';
        $poolSize = strlen($characterPool);

        for ($i = 0; $i < $length; $i++) {
            $randomIndex = random_int(0, $poolSize - 1);
            $password .= $characterPool[$randomIndex];
        }

        return $password;
    }
}