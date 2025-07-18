<?php

declare(strict_types=1);

namespace Sanmai\PHPStanRules\Tests\Fixtures\NoFinalClassesRule;

final class FinalClass
{
    public function doSomething(): void
    {
    }
}

final class AnotherFinalClass extends SomeParentClass
{
    public function doSomethingElse(): void
    {
    }
}

abstract class SomeParentClass
{
}