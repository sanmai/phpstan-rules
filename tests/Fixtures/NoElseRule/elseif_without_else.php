<?php

declare(strict_types=1);

function elseIfWithoutElse($value): string
{
    if ($value > 0) {
        return 'positive';
    } elseif ($value < 0) {
        return 'negative';
    }
    
    return 'zero';
}

function multipleElseIf($value): string
{
    if ($value > 10) {
        return 'large';
    } elseif ($value > 5) {
        return 'medium';
    } elseif ($value > 0) {
        return 'small';
    }
    
    return 'not positive';
}