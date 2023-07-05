<?php

namespace App\ImageManipulator\Domain;

use App\ImageManipulator\Domain\Decorator\CropImageInterface;
use App\ImageManipulator\Domain\Decorator\Exception\InvalidModificatorException;
use App\ImageManipulator\Domain\Decorator\Exception\ModificatorNotImplementedException;
use App\ImageManipulator\Domain\Decorator\ImageDecoratorInterface;
use App\ImageManipulator\Domain\Decorator\ImageManipulatorInterface;
use App\ImageManipulator\Domain\Decorator\ResizeImageInterface;
use App\ImageManipulator\Domain\ValueObject\DecoratorRegistry;
use App\ImageManipulator\Domain\ValueObject\DecoratorsRegistryItem;

class ModificationParameterResolver
{
    private const ALLOWED_MODIFICATORS = [
        'crop' => CropImageInterface::class,
        'resize' => ResizeImageInterface::class,
    ];

    public function __construct(private array $imageDecorators, private ImageManipulatorInterface $imageManipulator)
    {
    }

    public function resolveModificators(array $modificators): DecoratorRegistry
    {
        $resolvedModificators = [];

        foreach ($modificators as $modificatorData) {
            $modificatorParts = explode(':', $modificatorData);

            if (2 != count($modificatorParts)) {
                throw new InvalidModificatorException(sprintf('Invalid modificator `%s`', $modificatorData));
            }

            $modificator = strtolower($modificatorParts[0]);

            if (false === array_key_exists($modificator, self::ALLOWED_MODIFICATORS)) {
                throw new ModificatorNotImplementedException(sprintf('Modification %s is not implemented', $modificator));
            }

            $modificatorParameters = explode(',', $modificatorParts[1]);

            /** @var ImageDecoratorInterface $imageDecorator */
            foreach ($this->imageDecorators as $imageDecorator) {
                if ($imageDecorator::can($modificator, $modificatorParameters)) {
                    $resolvedModificators[] = new DecoratorsRegistryItem($imageDecorator, $modificatorParameters, $modificator);
                }
            }
        }

        if (0 == sizeof($resolvedModificators)) {
            throw new InvalidModificatorException('Please provide correct modificators!');
        }

        return new DecoratorRegistry($this->imageManipulator, $resolvedModificators);
    }
}
