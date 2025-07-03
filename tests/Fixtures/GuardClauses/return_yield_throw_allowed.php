<?php

declare(strict_types=1);

// Test that loops with if containing only return/yield/throw are allowed

// Return - should NOT be flagged
function findValid($items) {
    foreach ($items as $item) {
        if ($item->isValid()) {
            return $item;
        }
    }
}

// Yield - should NOT be flagged
function generateValid($items) {
    foreach ($items as $item) {
        if ($item->isValid()) {
            yield $item;
        }
    }
}

// Throw - should NOT be flagged
function validateItems($items) {
    foreach ($items as $item) {
        if (!$item->isValid()) {
            throw new \Exception('Invalid item');
        }
    }
}

// Mixed with other statements - should be flagged
foreach ($items as $item) {
    if ($item->needsProcessing()) { // error: Use guard clauses
        $item->process();
        return $item;
    }
}

// Multiple returns - should NOT be flagged
function findSpecial($items) {
    foreach ($items as $item) {
        if ($item->isSpecial()) {
            return $item;
            return null; // Unreachable but allowed
        }
    }
}

// Multiple yields - should NOT be flagged  
function generateMultiple($items) {
    foreach ($items as $item) {
        if ($item->hasData()) {
            yield $item->data1;
            yield $item->data2;
        }
    }
}