<?php
declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

final class Uuid
{
    private function __construct(private readonly string $value)
    {
    }

    public function value(): string
    {
        return $this->value;
    }

    public static function from(string $value): self
    {
        if (!self::isValidUuid($value)) {
            throw new \InvalidArgumentException('Invalid UUID format');
        }

        return new self($value);
    }

    private static function isValidUuid(string $value): bool
    {
        $pattern = '/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/i';

        return preg_match($pattern, $value) === 1;
    }
}
