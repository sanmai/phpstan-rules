<?php

declare(strict_types=1);

function checkWithEmpty($value): bool
{
    return empty($value); // Error: empty() is not allowed
}

function checkArrayWithEmpty(array $items): bool
{
    return empty($items); // Error: empty() is not allowed
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

function negatedEmpty($value): bool
{
    return !empty($value); // Error: empty() is not allowed
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