<?php

namespace App\Service;

class PasswordGenerator
{
    /**
     * Генерирует случайный пароль.
     *
     * @param int $length Длина пароля
     * @param bool $useNumbers Использовать цифры
     * @param bool $useSymbols Использовать специальные символы
     *
     * @return string Сгенерированный пароль
     */
    public function generate(int $length = 12, bool $useNumbers = true, bool $useSymbols = true): string
    {
        $chars = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        if ($useNumbers) {
            $chars .= '0123456789';
        }
        if ($useSymbols) {
            $chars .= '!@#$%^&*()-_=+[]{}<>?';
        }

        $password = '';
        $maxIndex = strlen($chars) - 1;

        for ($i = 0; $i < $length; $i++) {
            $password .= $chars[random_int(0, $maxIndex)];
        }

        return $password;
    }
}
