<?php

namespace App\ImageManipulator\Domain\Shared\Bus\Query;

interface QueryBusInterface
{
    public function ask(QueryInterface $query): ?ResponseInterface;
}
