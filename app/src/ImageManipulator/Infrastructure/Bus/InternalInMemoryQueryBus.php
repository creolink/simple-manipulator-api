<?php

namespace App\ImageManipulator\Infrastructure\Bus;

use App\ImageManipulator\Domain\Shared\Bus\Query\QueryBusInterface;
use App\ImageManipulator\Domain\Shared\Bus\Query\QueryInterface;
use App\ImageManipulator\Domain\Shared\Bus\Query\ResponseInterface;

class InternalInMemoryQueryBus implements QueryBusInterface
{
    public function __construct(private array $queryHandlers)
    {
    }

    public function ask(QueryInterface $query): ?ResponseInterface
    {
        $lastErrorMessage = '';
        $lastErrorTrace = '';

        foreach ($this->queryHandlers as $queryHandlerClass) {
            $reflectionMethod = new \ReflectionMethod($queryHandlerClass::class, '__invoke');

            $queryHandlerInvokeMethodParameters = $reflectionMethod->getParameters();
            $parameterType = (string) $queryHandlerInvokeMethodParameters[0]->getType();

            if ($parameterType === $query::class) {
                return $queryHandlerClass($query);
            }
        }

        throw new \Exception(sprintf('Missing Query Handler for %s. Error with message %s, %s', $query::class, $lastErrorMessage, $lastErrorTrace));
    }
}
