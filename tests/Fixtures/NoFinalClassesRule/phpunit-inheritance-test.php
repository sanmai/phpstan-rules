<?php

declare(strict_types=1);

namespace Sanmai\PHPStanRules\Tests\Fixtures\NoFinalClassesRule;

use PHPUnit\Framework\TestCase;

// Should be allowed: extends PHPUnit TestCase
final class MyTestCase extends TestCase
{
    public function testSomething(): void
    {
        $this->assertTrue(true);
    }
}

// Should be flagged: regular final class
final class RegularClass
{
    public function doSomething(): void
    {
    }
}