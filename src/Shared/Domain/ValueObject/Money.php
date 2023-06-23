<?php
declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

final class Money
{
    private function __construct(private readonly int $value)
    {
    }

    public static function from(int $value): self
    {
        return new self($value);
    }

    public static function fromFloat(float $value): self
    {
        return new self((int) round($value * 100));
    }

    public function value(): int
    {
        return $this->value;
    }

    public function toFloat(): float
    {
        return $this->value() / 100;
    }
}