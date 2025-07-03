<?php

declare(strict_types=1);

// Edge cases and special scenarios

// If with elseif - should NOT be flagged (more complex control flow)
foreach ($items as $item) {
    if ($item->type === 'A') { // OK - has elseif
        processTypeA($item);
    } elseif ($item->type === 'B') {
        processTypeB($item);
    }
}

// Empty loop body except for if
foreach ($items as $item) {
    if ($item) { // error: Use guard clauses
        // Just a comment
        doSomething($item);
    }
}

// If with else (else is forbidden by NoElseRule but not this rule's concern)
foreach ($items as $item) {
    if ($item->isActive()) { // OK - has else (though else itself is forbidden)
        $item->activate();
    } else {
        $item->deactivate();
    }
}

// Nested if inside single if
foreach ($items as $item) {
    if ($item->needsProcessing()) { // error: Use guard clauses
        if ($item->isValid()) {
            $item->process();
        }
    }
}

// Loop with only if containing yield
function generator($items) {
    foreach ($items as $item) {
        if ($item->isValid()) { // error: Use guard clauses
            yield $item;
        }
    }
}

// Loop with only if containing yield from
function generatorWithYieldFrom($items) {
    foreach ($items as $item) {
        if ($item instanceof \Generator) { // error: Use guard clauses
            yield from $item;
        }
    }
}

// Loop with only if containing yield from followed by continue - should NOT be flagged
function generatorWithYieldFromAndContinue($items) {
    foreach ($items as $item) {
        if ($item instanceof \Generator) { // OK - yield from with continue
            yield from $item;
            continue;
        }
    }
}

// Loop with only if containing regular yield - should be flagged
function generatorWithRegularYield($items) {
    foreach ($items as $item) {
        if ($item->hasData()) { // error: Use guard clauses
            yield $item->getData();
        }
    }
}

// Loop with only if containing throw - should NOT be flagged
foreach ($items as $item) {
    if ($item->isInvalid()) { // OK - contains only throw
        throw new \Exception('Invalid item');
    }
}

// Loop with only if containing exit - should NOT be flagged
foreach ($items as $item) {
    if ($item->isCritical()) { // OK - contains only exit
        exit(1);
    }
}

// Loop with only if containing die - should NOT be flagged
foreach ($items as $item) {
    if ($item->isFatal()) { // OK - contains only die
        die('Fatal error');
    }
}