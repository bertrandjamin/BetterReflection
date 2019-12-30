<?php

declare(strict_types=1);

namespace Roave\BetterReflection\Reflector;

use Roave\BetterReflection\Identifier\Identifier;
use Roave\BetterReflection\Identifier\IdentifierType;
use Roave\BetterReflection\Reflection\Reflection;
use Roave\BetterReflection\Reflection\ReflectionClass;
use Roave\BetterReflection\Reflector\Exception\IdentifierNotFound;
use Roave\BetterReflection\SourceLocator\Type\SourceLocator;
use function array_key_exists;

class ClassReflector implements Reflector
{
    /** @var SourceLocator */
    private $sourceLocator;

    /** @var (ReflectionClass|null)[] */
    private $cachedReflections = [];

    public function __construct(SourceLocator $sourceLocator)
    {
        $this->sourceLocator = $sourceLocator;
    }

    /**
     * Create a ReflectionClass for the specified $className.
     *
     * @return ReflectionClass
     *
     * @throws IdentifierNotFound
     */
    public function reflect(string $className) : Reflection
    {
        if (array_key_exists($className, $this->cachedReflections)) {
            $classInfo = $this->cachedReflections[$className];
        } else {
            $identifier = new Identifier($className, new IdentifierType(IdentifierType::IDENTIFIER_CLASS));
            /** @var ReflectionClass|null $classInfo */
            $classInfo = $this->sourceLocator->locateIdentifier($this, $identifier);
            $this->cachedReflections[$className] = $classInfo;
        }

        if ($classInfo === null) {
            if (!isset($identifier)) {
                $identifier = new Identifier($className, new IdentifierType(IdentifierType::IDENTIFIER_CLASS));
            }
            throw Exception\IdentifierNotFound::fromIdentifier($identifier);
        }

        return $classInfo;
    }

    /**
     * Get all the classes available in the scope specified by the SourceLocator.
     *
     * @return ReflectionClass[]
     */
    public function getAllClasses() : array
    {
        /** @var ReflectionClass[] $allClasses */
        $allClasses = $this->sourceLocator->locateIdentifiersByType(
            $this,
            new IdentifierType(IdentifierType::IDENTIFIER_CLASS)
        );

        return $allClasses;
    }
}
