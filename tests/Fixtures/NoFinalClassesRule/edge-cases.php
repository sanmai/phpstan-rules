<?php

declare(strict_types=1);

namespace Sanmai\PHPStanRules\Tests\Fixtures\NoFinalClassesRule;

use PHPUnit\Framework\TestCase;

// Direct inheritance from PHPUnit TestCase - should be exempted
final class DirectPHPUnitTest extends TestCase
{
    public function testDirect(): void
    {
        $this->assertTrue(true);
    }
}

// Regular final class - should be flagged
final class RegularFinalClass
{
    public function doSomething(): void
    {
    }
}

// Anonymous class - edge case for namespacedName being null
$anon = new class() {
    public function test(): void {}
};

// Final class with complex namespace - should be flagged
final class ComplexNamespaceClass
{
    public function complexMethod(): void
    {
    }
}