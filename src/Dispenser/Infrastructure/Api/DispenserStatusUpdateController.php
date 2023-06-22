<?php
declare(strict_types=1);

namespace App\Dispenser\Infrastructure\Api;

use App\DispenserEvent\Application\Command\UpdateStatusDispenserEventCommand;
use App\Shared\Domain\ValueObject\DateTimeValue;
use App\Shared\Domain\ValueObject\DispenserStatusType;
use App\Shared\Domain\ValueObject\Uuid;
use App\Shared\Infrastructure\Api\AbstractApiController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class DispenserStatusUpdateController extends AbstractApiController
{
    public function __invoke(Request $request): JsonResponse
    {

        try {
            $body = $this->getBody($request);
            $dispenserId = $request->get('id');

            $this->handleMessage(new UpdateStatusDispenserEventCommand(
                Uuid::from($dispenserId),
                DispenserStatusType::from($body['status']),
                DateTimeValue::createFromString($body['updated_at'])
            ));

            return $this->json([], Response::HTTP_ACCEPTED);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}