<?php
declare(strict_types=1);

namespace App\Dispenser\Application\Model;

use App\Shared\Domain\ValueObject\Money;

final class GetDispenserSpentResponse implements \JsonSerializable
{
    private function __construct(private Money $amount, private array $usages)
    {
    }

    public static function create(Money $amount, array $usages): self
    {
        return new self($amount, $usages);
    }

    public function amount(): Money
    {
        return $this->amount;
    }

    public function usages(): array
    {
        return $this->usages;
    }

    public function jsonSerialize(): array
    {
        return [
            'amount' => $this->amount()->toFloat(),
            'usages' => $this->usages(),
        ];
    }
}
