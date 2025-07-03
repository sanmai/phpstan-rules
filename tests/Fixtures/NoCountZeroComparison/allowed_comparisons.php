<?php

declare(strict_types=1);

function testCountWithOne(array $items): bool
{
    return count($items) === 1; // OK - not comparing with 0
}

function testCountWithTwo(array $items): bool
{
    return count($items) > 2; // OK - not comparing with 0
}

function testCountWithVariable(array $items, int $limit): bool
{
    return count($items) === $limit; // OK - not comparing with literal 0
}

function testEmptyArrayComparison(array $items): bool
{
    return $items === []; // OK - recommended approach
}

function testNotEmptyArrayComparison(array $items): bool
{
    return $items !== []; // OK - recommended approach
}

function testOtherFunctionWithZero(): bool
{
    return strlen('test') === 0; // OK - not count()
}

function testCountWithoutComparison(array $items): int
{
    return count($items); // OK - no comparison
}

function testCountInExpression(array $items): int
{
    return count($items) + 1; // OK - no comparison with 0
}

function testCountComparingCounts(array $items1, array $items2): bool
{
    return count($items1) === count($items2); // OK - comparing two counts
}

function testCountCaseSensitive(): bool
{
    // Test case sensitivity - COUNT should also be detected
    return COUNT([1, 2, 3]) === 0; // Error: count comparison with 0
}

function testNonFuncCall($obj): bool
{
    // Not a function call
    return $obj->count() === 0; // OK - method call, not function call
}

function testDynamicFunctionName(): bool
{
    $func = 'count';
    return $func([1, 2, 3]) === 0; // OK - dynamic function name
}