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
        // Comments should not affect the rule
        return $item;
    }
}

// Multiple returns - should be flagged
function findSpecial($items) {
    foreach ($items as $item) {
        if ($item->isSpecial()) {
            return $item;
            return null; // flagged
        }
    }
}

// multiple returns should be flagged, even without comments
function findSpecial2($items) {
    foreach ($items as $item) {
        if ($item->isSpecial()) {
            return $item;
            return null;
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

function generateMultiple2($items) {
    foreach ($items as $item) {
        if ($item->hasData()) {
            yield from $item->data1;
            yield from $item->data2;
        }
    }
}

// Exit - should NOT be flagged
function exitConditional($items) {
    foreach ($items as $item) {
        if ($item->hasData()) {
            exit(1);
        }
    }
}

// Break - should NOT be flagged
function walkBreak($items) {
    foreach ($items as $item) {
        if ($item->isSpecial()) {
            break; // should NOT be flagged
        }
    }
}

// Assignment - should NOT be flagged
function minIterator(Traversable $items) {
    foreach ($items as $value) {
        if ($value < $min) {
            $min = $value; // should NOT be flagged
        }
    }

    return $min;
}

// Side effects - should NOT be flagged
function sideEffects($items) {
    foreach ($items as $item) {
        if ($item->doIt()) {
            // should NOT be flagged
            // Any number of comments is allowed
            // and this comment is OK
        }
    }
}

// Mixed generator - should not be flagged
function mixedGenerator($items) {
    foreach ($items as $item) {
        if ($item->hasData()) {
            yield from $item->data1;
            yield from $item->data2;
            yield $item->data3;
            $item->process();
            $item->log(); // Should not be flagged
        }
    }
}
