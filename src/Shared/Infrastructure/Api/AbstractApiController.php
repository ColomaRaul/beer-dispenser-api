<?php
declare(strict_types=1);

namespace App\Shared\Infrastructure\Api;

use App\Shared\Application\Command\CommandInterface;
use App\Shared\Application\Query\QueryInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Messenger\HandleTrait;
use Symfony\Component\Messenger\MessageBusInterface;

abstract class AbstractApiController extends AbstractController
{
    use HandleTrait;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    protected function getBody(Request $request): ParameterBag
    {
        return $request->request;
    }

    protected function handleMessage(CommandInterface|QueryInterface $commandQuery)
    {
        return $this->handle($commandQuery);
    }
}
