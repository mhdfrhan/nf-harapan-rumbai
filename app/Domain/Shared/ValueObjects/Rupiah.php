<?php

namespace App\Domain\Shared\ValueObjects;

use InvalidArgumentException;
use Stringable;

final readonly class Rupiah implements Stringable
{
    public function __construct(public int $amount)
    {
        if ($amount < 0) {
            throw new InvalidArgumentException('Nilai rupiah tidak boleh negatif.');
        }
    }

    public static function zero(): self
    {
        return new self(0);
    }

    public function add(self $other): self
    {
        return new self($this->amount + $other->amount);
    }

    public function subtract(self $other): self
    {
        if ($other->amount > $this->amount) {
            throw new InvalidArgumentException('Hasil pengurangan rupiah tidak boleh negatif.');
        }

        return new self($this->amount - $other->amount);
    }

    public function multiply(int $quantity): self
    {
        if ($quantity < 0) {
            throw new InvalidArgumentException('Pengali rupiah tidak boleh negatif.');
        }

        return new self($this->amount * $quantity);
    }

    public function formatted(): string
    {
        return 'Rp'.number_format($this->amount, 0, ',', '.');
    }

    public function __toString(): string
    {
        return (string) $this->amount;
    }
}
