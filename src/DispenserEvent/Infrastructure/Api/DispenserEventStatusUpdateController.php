<?php
declare(strict_types=1);

namespace App\DispenserEvent\Infrastructure\Api;

use App\DispenserEvent\Application\Command\UpdateStatusDispenserEventCommand;
use App\DispenserEvent\Application\Exception\DispenserEventAlreadyUpdateSameStatusApplicationException;
use App\DispenserEvent\Application\Exception\DispenserNotFoundApplicationException;
use App\Shared\Domain\ValueObject\DateTimeValue;
use App\Shared\Domain\ValueObject\DispenserStatusType;
use App\Shared\Domain\ValueObject\Uuid;
use App\Shared\Infrastructure\Api\AbstractApiController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

final class DispenserEventStatusUpdateController extends AbstractApiController
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

            return $this->json('Status of the tap changed correctly', Response::HTTP_ACCEPTED);
        } catch (DispenserNotFoundApplicationException $e) {
            return $this->json($e->getMessage(), Response::HTTP_NOT_FOUND);
        } catch (DispenserEventAlreadyUpdateSameStatusApplicationException $e) {
            return $this->json($e->getMessage(), Response::HTTP_CONFLICT);
        } catch (\Exception $e) {
            return $this->json('Unexpected API error', Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}