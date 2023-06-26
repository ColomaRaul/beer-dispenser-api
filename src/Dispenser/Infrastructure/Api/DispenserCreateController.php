<?php
declare(strict_types=1);

namespace App\Dispenser\Infrastructure\Api;

use App\Dispenser\Application\Command\CreateDispenserCommand;
use App\Dispenser\Infrastructure\Api\UI\DispenserCreateResponse;
use App\Shared\Domain\ValueObject\Uuid;
use App\Shared\Infrastructure\Api\AbstractApiController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class DispenserCreateController extends AbstractApiController
{
    public function __invoke(Request $request): JsonResponse
    {
        try {
            $body = $this->getBody($request);

            $flowVolume = (float) $body['flow_volume'];
            $id = Uuid::generate();
            $this->handleMessage(new CreateDispenserCommand($id, $flowVolume));

            return $this->json(DispenserCreateResponse::responseOk($id->value(), $flowVolume), Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
