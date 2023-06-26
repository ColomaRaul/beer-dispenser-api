<?php
declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

final class Money
{
    private function __construct(private int $value)
    {
    }

    public static function from(int $value): self
    {
        return new self($value);
    }

    public function value(): int
    {
        return $this->value;
    }

    public function toFloat(): float
    {
        return $this->value() / 100;
    }

    public function add(Money $value): self
    {
        return new self($this->value + $value->value);
    }

    public function diff(Money $value): self
    {
        return new self($this->value - $value->value);
    }
}