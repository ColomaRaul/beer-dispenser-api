<?php
declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

use DateTimeInterface;
use DateTimeImmutable;
use DateTimeZone;

final class DateTimeValue
{
    private function __construct(private DateTimeInterface $dateTime)
    {
    }

    public static function create(?DateTimeInterface $dateTime = null): self
    {
        if (null === $dateTime) {
            $dateTime = new DateTimeImmutable();
        }

        return new self($dateTime);
    }

    /**
     * @throws \Exception
     */
    public static function createFromString(string $dateTimeString, string $timezone = null): self
    {
        $timezone = $timezone ?? date_default_timezone_get();
        $dateTime = new DateTimeImmutable($dateTimeString, new DateTimeZone($timezone));

        return new self($dateTime);
    }

    public function toAtomString(): string
    {
        return $this->dateTime->format(DATE_ATOM);
    }

    public function value(): DateTimeInterface
    {
        return $this->dateTime;
    }

    public function secondsBetweenDates(DateTimeValue $otherValue): int
    {
        $interval = $this->dateTime->diff($otherValue->value());

        return $interval->days * 24 * 60 * 60 + $interval->h * 60 * 60 + $interval->i * 60 + $interval->s;
    }
}
