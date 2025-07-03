<?php

// This file contains examples that should trigger the RequireGuardClausesInLoopsRule

// If statement with code after it - should use guard clause
foreach ($items as $item) {
    if ($item['active']) { // error: Use guard clauses
        processItem($item);
    }
    logItem($item); // Statement after if
}

// If statement with multiple statements inside - should use guard clause
foreach ($items as $item) {
    if ($item['valid']) { // error: Use guard clauses
        validateItem($item);
        processItem($item);
        saveItem($item);
    }
}

// Good - using guard clause
foreach ($items as $item) {
    if (!$item['active']) { // No error - contains early return
        continue;
    }
    processItem($item);
}

// Good - if with else
foreach ($items as $item) {
    if ($item['active']) { // No error - has else branch
        processItem($item);
    } else {
        skipItem($item);
    }
}

// Good - single statement in if at end of loop
foreach ($items as $item) {
    logItem($item);
    if ($item['special']) { // No error - last statement with single action
        markSpecial($item);
    }
}

// Bad - single if but with multiple statements inside
while ($row = fetchRow()) {
    if ('active' === $row['status']) { // error: Use guard clauses
        updateRow($row);
        notifyUpdate($row);
    }
}

// Good - if contains only early returns
for ($i = 0; $i < count($items); $i++) {
    if (null === $items[$i]) { // No error - contains only early return
        continue;
    }
    process($items[$i]);
}
