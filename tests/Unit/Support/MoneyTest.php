<?php

use App\Support\Money;

it('converts decimal amounts to integer cents exactly', function () {
    expect(Money::toCents('0.10'))->toBe(10)
        ->and(Money::toCents('100000.99'))->toBe(10000099)
        ->and(Money::toCents('-12.05'))->toBe(-1205)
        ->and(Money::toCents('7'))->toBe(700);
});

it('formats integer cents without floating point division', function () {
    expect(Money::fromCents(10))->toBe('0.10')
        ->and(Money::fromCents(10000099))->toBe('100000.99')
        ->and(Money::fromCents(-1205))->toBe('-12.05')
        ->and(Money::multiply('100000.05', 3))->toBe('300000.15');
});
