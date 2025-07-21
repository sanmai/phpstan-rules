<?php

declare(strict_types=1);

namespace Sanmai\PHPStanRules\TestFixtures;

// This should be flagged - final class in src directory
final class RegularFinalClass
{
    public function doSomething(): void
    {
    }
}

// This should also be flagged
final class AnotherFinalClass
{
    public function doSomethingElse(): void
    {
    }
}

// This should not be flagged - not final
class RegularClass
{
    public function regularMethod(): void
    {
    }
}