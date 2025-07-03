<?php

declare(strict_types=1);

// Test cases that already use guard clauses correctly - these should NOT be flagged

// Basic guard clause with continue
foreach ($items as $item) {
    if (!$item->isValid()) { // OK - using guard clause
        continue;
    }
    $item->process();
}

// Guard clause with early return
foreach ($items as $item) {
    if ($item->shouldSkip()) { // OK - using early return
        return;
    }
    $item->process();
}

// Guard clause with break
foreach ($items as $item) {
    if ($item->isLast()) { // OK - using break
        break;
    }
    $item->process();
}

// Multiple guard clauses
foreach ($items as $item) {
    if (!$item->isValid()) { // OK - guard clause
        continue;
    }
    
    if ($item->isSpecial()) { // OK - has code before
        $item->handleSpecial();
    }
    
    $item->process();
}

// Loop with only guard clause (early return pattern)
foreach ($items as $item) {
    if ($item->shouldSkip()) { // OK - contains only early return
        continue;
    }
}