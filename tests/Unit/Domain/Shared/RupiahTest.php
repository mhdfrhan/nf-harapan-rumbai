<?php

use App\Domain\Shared\ValueObjects\Rupiah;

it('stores and formats whole rupiah amounts', function () {
    $money = new Rupiah(125000);

    expect($money->amount)->toBe(125000)
        ->and($money->formatted())->toBe('Rp125.000')
        ->and((string) $money)->toBe('125000');
});

it('supports safe arithmetic without floating point values', function () {
    $total = (new Rupiah(12500))
        ->multiply(4)
        ->add(new Rupiah(5000))
        ->subtract(new Rupiah(10000));

    expect($total->amount)->toBe(45000);
});

it('rejects negative amounts', function () {
    expect(fn () => new Rupiah(-1))->toThrow(InvalidArgumentException::class);
});

it('rejects subtraction that produces a negative amount', function () {
    expect(fn () => (new Rupiah(1000))->subtract(new Rupiah(1500)))
        ->toThrow(InvalidArgumentException::class);
});
