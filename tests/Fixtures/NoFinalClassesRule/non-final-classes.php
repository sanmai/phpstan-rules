<?php

declare(strict_types=1);

namespace Sanmai\PHPStanRules\Tests\Fixtures\NoFinalClassesRule;

class RegularClass
{
    public function doSomething(): void
    {
    }
}

abstract class AbstractClass
{
    abstract public function doSomething(): void;
}

/**
 * @final
 */
class ClassWithFinalAnnotation
{
    public function doSomething(): void
    {
    }
}

readonly class ReadonlyClass
{
    public function __construct(
        public string $value
    ) {
    }
}

interface SomeInterface
{
    public function doSomething(): void;
}

trait SomeTrait
{
    public function doSomething(): void
    {
    }
}

class UserService
{
    public function getUser(int $id)
    {
        // ...
    }

    // Private methods are not prohibited
    private function updateUsers(): void
    {

    }

    // Just as final methods are still discretionary
    final public function getAll(): iterable
    {
        yield;
    }
}
