<?php

declare(strict_types=1);

namespace PHPUnit\Framework;

// Fake TestCase for testing
abstract class TestCase
{
    protected function assertTrue(bool $condition): void {}
}

namespace Sanmai\PHPStanRules\Tests\Fixtures\NoFinalClassesRule;

use PHPUnit\Framework\TestCase;

// Should be allowed: extends PHPUnit TestCase directly
final class DirectTestCase extends TestCase
{
    public function testSomething(): void
    {
        $this->assertTrue(true);
    }
}

// Should be allowed: extends abstract class that extends TestCase
abstract class AbstractTestCase extends TestCase
{
    abstract public function getTestData(): array;
}

final class ConcreteTestCase extends AbstractTestCase
{
    public function getTestData(): array
    {
        return ['data'];
    }
}

// Should be flagged: final class with no inheritance
final class RegularFinalClass
{
    public function doSomething(): void
    {
    }
}