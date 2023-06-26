<?php
declare(strict_types=1);

namespace App\Dispenser\Application\Service;

use App\Dispenser\Application\Model\GetDispenserSpentResponse;
use App\Dispenser\Application\Query\GetDispenserSpentQuery;
use App\Dispenser\Domain\Service\GetDispenserSpentService;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

final class GetDispenserSpentHandler implements MessageHandlerInterface
{
    public function __construct(private GetDispenserSpentService $service)
    {
    }

    public function __invoke(GetDispenserSpentQuery $query): GetDispenserSpentResponse
    {
        $result = $this->service->execute($query->id());

        return GetDispenserSpentResponse::create($result['amount'], $result['usages']);
    }
}