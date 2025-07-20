<?php

declare(strict_types=1);

namespace Sanmai\PHPStanRules\Tests\Fixtures\NoFinalClassesRule;

// Simulate PHPUnit TestCase for testing since PHPStan test environment may not load it
class TestCase 
{
    protected function assertTrue(bool $condition): void {}
}

// This should be allowed if PHPUnit was properly loaded
final class MyTestCase extends TestCase
{
    public function testSomething(): void
    {
        $this->assertTrue(true);
    }
}

// Should be allowed: extends undefined class (when PHPUnit not installed)
final class MyUndefinedExtension extends UndefinedClass
{
    public function doSomething(): void
    {
    }
}

// Should be allowed: extends class ending with TestCase
final class CustomTestCase extends SomeTestCase
{
    public function testCustom(): void
    {
    }
}

// Abstract test case that extends PHPUnit TestCase
abstract class AbstractTestCase extends TestCase
{
    abstract public function getTestData(): array;
}

// Should be allowed: extends abstract class that extends TestCase
final class ConcreteTestCase extends AbstractTestCase
{
    public function getTestData(): array
    {
        return ['test' => 'data'];
    }
}

// Should still be flagged: final class with no extends
final class StillFinalClass
{
    public function doSomething(): void
    {
    }
}
