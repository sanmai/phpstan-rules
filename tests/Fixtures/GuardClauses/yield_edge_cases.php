<?php

declare(strict_types=1);

// Test specific yield and early return edge cases

// Yield from as last statement - should be flagged
function yieldFromAsLastStatement($items) {
    foreach ($items as $item) {
        if ($item instanceof \Generator) { // error: Use guard clauses
            yield from $item;
        }
    }
}

// Yield from NOT as last statement with another statement after - should be flagged
function yieldFromWithStatementAfter($items) {
    foreach ($items as $item) {
        if ($item instanceof \Generator) { // error: Use guard clauses
            yield from $item;
            $item->mark();
        }
    }
}

// Break statement - should NOT be flagged
foreach ($items as $item) {
    if ($item->isLast()) { // OK - contains only break
        break;
    }
}

// Expression with non-early-return - should be flagged
foreach ($items as $item) {
    if ($item->isSpecial()) { // error: Use guard clauses
        $item->doSomething();
    }
}

// Expression that's not Throw_, Exit_, YieldFrom, or Yield_ - should be flagged
foreach ($items as $item) {
    if ($item->needsProcessing()) { // error: Use guard clauses
        $result = $item->process();
    }
}