<?php
declare(strict_types=1);

namespace App\Dispenser\Infrastructure\Api;

use App\Dispenser\Application\Query\GetDispenserSpentQuery;
use App\Shared\Domain\ValueObject\Uuid;
use App\Shared\Infrastructure\Api\AbstractApiController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class DispenserGetSpentController extends AbstractApiController
{
    public function __invoke(Request $request): JsonResponse
    {
        try {
            $dispenserId = $request->get('id');
            $response = $this->handleMessage(new GetDispenserSpentQuery(Uuid::from($dispenserId)));

            return $this->json($response, Response::HTTP_OK);
        } catch (\Exception $e) {
            return $this->json($e->getMessage());
        }
    }
}
