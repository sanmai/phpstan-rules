<?php

declare(strict_types=1);

function checkString(string $str): bool
{
    return empty($str); // Error: empty() on string - could miss "0"
}

function checkNullableString(?string $str): bool
{
    return empty($str); // Error: empty() on nullable string - could miss "0"
}

function checkUnionWithString(string|int $value): bool
{
    return empty($value); // Error: empty() on union containing string
}

function checkMixedStringArray(string|array $value): bool
{
    return empty($value); // Error: empty() on union containing string
}

function tagSearch(string $tag): array
{
    if (empty($tag)) { // Error: empty() on string - "0" would be treated as empty!
        throw new \InvalidArgumentException('Tag cannot be empty');
    }
    return [];
}

function processInput(?string $input): void
{
    if (!empty($input)) { // Error: empty() on nullable string
        echo $input;
    }
}

// These should NOT trigger the string-specific rule (handled by NoEmptyRule)
function checkArray(array $items): bool
{
    return empty($items); // Not a string type
}

function checkInt(int $num): bool
{
    return empty($num); // Not a string type
}

function checkNullableArray(?array $items): bool
{
    return empty($items); // Not a string type (and allowed by NoEmptyRule)
}

function searchTag(string $tag): array
{
    if (empty($tag)) { // Error:
        // This executes! '0' is considered "empty"
        throw new \InvalidArgumentException('No tag provided');
    }

    return []; // search for tag '0' is impossible
}

searchTag('0');

function checkUnknown($str): bool
{
    return empty($str); // Error: empty() is not allowed (mixed contains string types)
}

function checkMixed(mixed $str): bool
{
    return empty($str); // Error: empty() is not allowed (mixed contains string types)
}
