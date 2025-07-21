<?php

declare(strict_types=1);

// This file tests direct inheritance from PHPUnit\Framework\TestCase
// It must be in global namespace to test edge cases

use PHPUnit\Framework\TestCase;

// This should be exempted due to direct inheritance
final class DirectInheritanceTest extends TestCase
{
    public function testDirectInheritance(): void
    {
        $this->assertTrue(true);
    }
}