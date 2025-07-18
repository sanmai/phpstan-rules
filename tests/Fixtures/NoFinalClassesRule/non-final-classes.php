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