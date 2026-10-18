<?php

namespace App\Domain\Shared\ValueObjects;

use InvalidArgumentException;

final readonly class Quantity
{
    public const SCALE = 10000;

    public function __construct(public int $scaled) {}

    public static function from(string|int $value): self
    {
        $normalized = str_replace(',', '.', trim((string) $value));
        if (! preg_match('/^-?\d+(?:\.\d{1,4})?$/', $normalized)) {
            throw new InvalidArgumentException('Kuantitas harus berupa angka dengan maksimal empat angka desimal.');
        }

        $negative = str_starts_with($normalized, '-');
        $normalized = ltrim($normalized, '-');
        [$whole, $fraction] = array_pad(explode('.', $normalized, 2), 2, '');
        $scaled = ((int) $whole * self::SCALE) + (int) str_pad($fraction, 4, '0');

        return new self($negative ? -$scaled : $scaled);
    }

    public function multiply(self $factor): self
    {
        return new self(intdiv(($this->scaled * $factor->scaled) + intdiv(self::SCALE, 2), self::SCALE));
    }

    public function rupiahSubtotal(int $unitPrice): int
    {
        return intdiv(($unitPrice * $this->scaled) + intdiv(self::SCALE, 2), self::SCALE);
    }

    public function decimal(): string
    {
        $negative = $this->scaled < 0 ? '-' : '';
        $absolute = abs($this->scaled);
        $fraction = str_pad((string) ($absolute % self::SCALE), 4, '0', STR_PAD_LEFT);

        return $negative.intdiv($absolute, self::SCALE).'.'.$fraction;
    }
}
