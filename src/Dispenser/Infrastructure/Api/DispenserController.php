<?php
declare(strict_types=1);

namespace App\Dispenser\Infrastructure\Api;

use App\Dispenser\Application\Command\CreateDispenserCommand;
use App\Shared\Domain\ValueObject\Uuid;
use App\Shared\Infrastructure\Api\AbstractApiController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class DispenserController extends AbstractApiController
{
    public function __invoke(Request $request): JsonResponse
    {
        $body = $this->getBody($request);

        try {
            $flowVolume = (float)$body->get('flow_volume');
            $id = Uuid::generate();

            $this->handleMessage(new CreateDispenserCommand($id, $flowVolume));

            return $this->json(['id' => $id->value(), 'flow_volume' => $flowVolume], Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
