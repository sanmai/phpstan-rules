<?php

declare(strict_types=1);

function simpleIfElse($value): string
{
    if ($value > 0) {
        return 'positive';
    } else { // Error: else statement
        return 'not positive';
    }
}

function nestedIfElse($a, $b): string
{
    if ($a > 0) {
        if ($b > 0) {
            return 'both positive';
        } else { // Error: else statement
            return 'a positive, b not';
        }
    } else { // Error: else statement
        return 'a not positive';
    }
}

function elseIfWithElse($value): string
{
    if ($value > 0) {
        return 'positive';
    } elseif ($value < 0) {
        return 'negative';
    } else { // Error: else statement
        return 'zero';
    }
}

function elseInLoop(array $items): void
{
    foreach ($items as $item) {
        if ($item > 0) {
            echo 'positive';
        } else { // Error: else statement
            echo 'not positive';
        }
    }
}