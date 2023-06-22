<?php
declare(strict_types=1);

namespace App\Dispenser\Infrastructure\Api\UI;

use Symfony\Component\Serializer\Annotation\SerializedName;

final class DispenserCreateResponse implements \JsonSerializable
{
    /** @SerializedName("id") */
    private ?string $id;
    /** @SerializedName("flow_volume") */
    private ?float $flowVolume;

    private function __construct(?string $id = null, ?float $flowVolume = null)
    {
        $this->id = $id;
        $this->flowVolume = $flowVolume;
    }

    public static function responseOk(string $id, float $flowVolume): self
    {
        return new self($id, $flowVolume);
    }

    public function id(): ?string
    {
        return $this->id;
    }

    public function flowVolume(): ?float
    {
        return $this->flowVolume;
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id(),
            'flow_volume' => $this->flowVolume(),
        ];
    }
}
