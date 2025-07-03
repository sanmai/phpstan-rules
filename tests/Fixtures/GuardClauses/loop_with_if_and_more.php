<?php

declare(strict_types=1);

// Test cases where loops contain an if statement AND other code - these should NOT be flagged

// If with statement after
foreach ($items as $item) {
    if ($item->needsProcessing()) { // OK - has statement after
        $item->process();
    }
    $item->log();
}

// Statement before if
foreach ($items as $item) {
    $item->prepare();
    if ($item->isReady()) { // OK - has statement before
        $item->execute();
    }
}

// Multiple statements including if
foreach ($items as $item) {
    $item->validate();
    if ($item->isValid()) { // OK - multiple statements in loop
        $item->save();
    }
    $item->cleanup();
}

// Buffer management example
foreach ($stream as $data) {
    if (count($buffer) >= $limit) { // OK - has statement after
        array_shift($buffer);
    }
    $buffer[] = $data;
}

// Conditional increment
$count = 0;
foreach ($items as $item) {
    if ($item->isSpecial()) { // OK - has statement after
        $count++;
    }
    processItem($item);
}

// Multiple ifs with other code
foreach ($records as $record) {
    $record->load();
    if ($record->needsUpdate()) { // OK - multiple statements
        $record->update();
    }
    if ($record->needsValidation()) { // OK - multiple statements
        $record->validate();
    }
    $record->save();
}