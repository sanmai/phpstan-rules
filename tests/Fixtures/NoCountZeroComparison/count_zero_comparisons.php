<?php

declare(strict_types=1);

function testCountEquals(array $items): bool
{
    return count($items) == 0; // Error: count comparison with 0
}

function testCountIdentical(array $items): bool
{
    return count($items) === 0; // Error: count comparison with 0
}

function testCountNotEqual(array $items): bool
{
    return count($items) != 0; // Error: count comparison with 0
}

function testCountNotIdentical(array $items): bool
{
    return count($items) !== 0; // Error: count comparison with 0
}

function testCountGreaterThan(array $items): bool
{
    return count($items) > 0; // Error: count comparison with 0
}

function testCountLessThan(array $items): bool
{
    return count($items) < 0; // Error: count comparison with 0
}

function testZeroEqualsCount(array $items): bool
{
    return 0 == count($items); // Error: count comparison with 0
}

function testZeroIdenticalCount(array $items): bool
{
    return 0 === count($items); // Error: count comparison with 0
}

function testZeroNotEqualCount(array $items): bool
{
    return 0 != count($items); // Error: count comparison with 0
}

function testZeroLessThanCount(array $items): bool
{
    return 0 < count($items); // Error: count comparison with 0
}

function testInIfCondition(array $items): void
{
    if (count($items) === 0) { // Error on this line
        echo "empty";
    }
    
    if (count($items) != 0) { // Error on this line
        echo "not empty";
    }
}