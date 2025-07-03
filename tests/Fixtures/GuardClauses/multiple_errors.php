<?php

declare(strict_types=1);

// Test file with multiple errors to ensure we return all of them

// First error
foreach ($items as $item) {
    if ($item->isActive()) { // error: Use guard clauses
        $item->process();
    }
}

// Second error - different loop type
while ($condition) {
    if ($shouldProcess) { // error: Use guard clauses
        doSomething();
    }
}

// Third error - nested loops with both having issues
foreach ($groups as $group) {
    if ($group->isEnabled()) { // error: Use guard clauses
        foreach ($group->items as $item) {
            if ($item->isValid()) { // error: Use guard clauses
                $item->handle();
            }
        }
    }
}

// Empty loop - should NOT be flagged (no statements)
foreach ($items as $item) {
    // Empty body
}

// Loop with only comment - should NOT be flagged
foreach ($items as $item) {
    // Just a comment
}