<?php

namespace App\Support;

final class Money
{
    /**
     * Convert a decimal amount to integer cents without floating-point arithmetic.
     */
    public static function toCents(string|int|float $amount): int
    {
        $value = trim((string) $amount);
        $negative = str_starts_with($value, '-');
        $value = ltrim($value, '+-');
        $parts = explode('.', $value, 2);
        $whole = $parts[0] === '' ? '0' : $parts[0];
        $fraction = str_pad(substr($parts[1] ?? '', 0, 2), 2, '0');

        $cents = ((int) $whole) * 100 + (int) $fraction;

        return $negative ? -$cents : $cents;
    }

    /**
     * Convert integer cents to a two-decimal amount.
     */
    public static function fromCents(int $cents): string
    {
        $sign = $cents < 0 ? '-' : '';
        $absolute = abs($cents);

        return $sign.intdiv($absolute, 100).'.'.str_pad((string) ($absolute % 100), 2, '0', STR_PAD_LEFT);
    }

    public static function multiply(string|int|float $amount, int $quantity): string
    {
        return self::fromCents(self::toCents($amount) * $quantity);
    }
}
