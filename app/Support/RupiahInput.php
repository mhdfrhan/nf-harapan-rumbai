<?php

namespace App\Support;

use Illuminate\Validation\ValidationException;

final class RupiahInput
{
    public static function parse(string|int|null $value, string $field = 'amount'): int
    {
        $input = preg_replace('/^Rp\s*/i', '', trim((string) $value));
        $input = preg_replace('/\s+/', '', (string) $input);
        if ($input === '' || ! preg_match('/^\d+(?:[.,]\d{3})*$/', $input)) {
            throw ValidationException::withMessages([$field => 'Nilai rupiah tidak valid.']);
        }
        $normalized = str_replace(['.', ','], '', $input);
        $amount = (int) $normalized;
        if ($amount <= 0) {
            throw ValidationException::withMessages([$field => 'Nilai rupiah harus lebih dari nol.']);
        }

        return $amount;
    }
}
