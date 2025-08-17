<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class SecurePassword implements Rule
{
    public function passes($attribute, $value)
    {
        // Al menos 8 caracteres
        if (strlen($value) < 8) {
            return false;
        }

        // Al menos 1 mayúscula
        if (!preg_match('/[A-Z]/', $value)) {
            return false;
        }

        // Al menos 1 minúscula
        if (!preg_match('/[a-z]/', $value)) {
            return false;
        }

        // Al menos 3 números no correlativos
        $numbers = preg_replace('/[^0-9]/', '', $value);
        if (strlen($numbers) < 3) {
            return false;
        }

        // Verificar si hay 3 números correlativos (123, 234, etc.)
        for ($i = 0; $i <= strlen($numbers) - 3; $i++) {
            $sequence = substr($numbers, $i, 3);
            if ($sequence[0] + 1 == $sequence[1] && $sequence[1] + 1 == $sequence[2]) {
                return false;
            }
        }

        return true;
    }

    public function message()
    {
        return 'La contraseña debe tener al menos 8 caracteres, una mayúscula, una minúscula y al menos 3 números no consecutivos.';
    }
}