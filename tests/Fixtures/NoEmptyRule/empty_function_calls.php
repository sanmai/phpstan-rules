<?php

declare(strict_types=1);

function checkWithEmpty($value): bool
{
    return empty($value); // Error: empty() is not allowed
}

function checkArrayWithEmpty(array $items): bool
{
    return empty($items); // Error: empty() is not allowed (use $items === [] instead)
}

function checkStringWithEmpty(string $str): bool
{
    return empty($str); // Error: empty() is not allowed
}

function conditionalWithEmpty($value): string
{
    if (empty($value)) { // Error: empty() is not allowed
        return 'empty';
    }
    return 'not empty';
}

function negatedEmpty(string|array $value): bool
{
    return !empty($value); // Error: empty() is not allowed (composite type includes anything else but array)
}

function emptyInLoop(array $items): void
{
    foreach ($items as $item) {
        if (empty($item)) { // Error: empty() is not allowed
            continue;
        }
        echo $item;
    }
}

function ternaryWithEmpty($value): string
{
    return empty($value) ? 'empty' : 'not empty'; // Error: empty() is not allowed
}

function checkArrayNullableArrays(?array $items): bool
{
    return empty($items); // empty with nullable arrays is allowed
}

function checkArrayNullableArrays2(null|array $items): bool
{
    return empty($items); // empty with nullable arrays is allowed
}

function checkUnionWithMultipleTypes(string|array|null $value): bool
{
    return empty($value); // Error: empty() is not allowed (union has more than just array|null)
}

function checkArrayOrInt(array|int $value): bool
{
    return empty($value); // Error: empty() is not allowed (union contains non-null non-array type)
}

function checkThreeWayUnion(array|null|false $value): bool
{
    return empty($value); // Error: empty() is not allowed (union has more than 2 types)
}

function checkSingleNullType(?string $value): bool
{
    return empty($value); // Error: empty() is not allowed (this is string|null, not array|null)
}

function checkUnknown($str): bool
{
    return empty($str); // Error: empty() on string - could miss "0"
}

function checkMixed(mixed $str): bool
{
    return empty($str); // Error: empty() on string - could miss "0"
}
