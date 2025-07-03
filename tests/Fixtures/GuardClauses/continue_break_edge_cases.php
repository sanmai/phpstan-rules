<?php

declare(strict_types=1);

// Test specific edge cases for continue and break handling

// Multiple continues in sequence - should be flagged (has non-early-return statement)
foreach ($items as $item) {
    if ($item->shouldSkip()) { // error: Use guard clauses
        logSkip($item);
        continue;
        continue; // Unreachable but tests the continue path
    }
}

// Multiple returns in sequence - should be flagged (return with value is different)
function multipleReturns($items) {
    foreach ($items as $item) {
        if ($item->shouldReturn()) { // error: Use guard clauses
            return $item;
            return null; // Unreachable but tests the return path
        }
    }
}

// Break after other statement - mixed content
foreach ($items as $item) {
    if ($item->isLast()) { // error: Use guard clauses
        $item->process();
        break;
    }
}

// Multiple Expression statements with early returns - should NOT be flagged
foreach ($items as $item) {
    if ($item->isInvalid()) { // OK - all are early returns
        throw new \Exception('Invalid');
        exit(1);
        die('Error');
    }
}

// If with empty body - should be flagged
foreach ($items as $item) {
    if ($item->isEmpty()) { // error: Use guard clauses
        // Empty body
    }
}

// Expression statement that's not an early return type
foreach ($items as $item) {
    if ($item->needsWork()) { // error: Use guard clauses
        functionCall($item);
    }
}