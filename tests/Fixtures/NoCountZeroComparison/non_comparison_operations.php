<?php

declare(strict_types=1);

function testAddition(array $items): int
{
    return count($items) + 0; // OK - addition, not comparison
}

function testSubtraction(array $items): int
{
    return count($items) - 0; // OK - subtraction, not comparison
}

function testMultiplication(array $items): int
{
    return count($items) * 0; // OK - multiplication, not comparison
}

function testBitwiseAnd(array $items): int
{
    return count($items) & 0; // OK - bitwise operation, not comparison
}

function testLogicalAnd(array $items): bool
{
    return count($items) && 0; // OK - logical AND, not comparison
}

function testAssignment(array $items): int
{
    $result = 0;
    $result += count($items); // OK - assignment, not comparison
    return $result;
}