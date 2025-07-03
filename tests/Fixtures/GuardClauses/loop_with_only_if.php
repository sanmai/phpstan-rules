<?php

declare(strict_types=1);

// Test cases where loops contain ONLY an if statement - these should be flagged

// Basic case - foreach with only if
foreach ($items as $item) {
    if ($item->isActive()) { // error: Use guard clauses
        $item->process();
    }
}

// While loop with only if
while ($row = fetchNext()) {
    if ($row->isValid()) { // error: Use guard clauses
        processRow($row);
    }
}

// For loop with only if
for ($i = 0; $i < count($items); $i++) {
    if ($items[$i] !== null) { // error: Use guard clauses
        process($items[$i]);
    }
}

// Do-while with only if
$i = 0;
do {
    if ($data[$i] > 0) { // error: Use guard clauses
        handlePositive($data[$i]);
        $i++;
    }
} while ($i < 10);

// Multiple statements inside if - still should be flagged
foreach ($users as $user) {
    if ($user->hasPermission('edit')) { // error: Use guard clauses
        $user->grantAccess();
        $user->logActivity();
        $user->notify();
    }
}

// Nested loops - inner loop has only if
foreach ($groups as $group) {
    processGroup($group);
    foreach ($group->members as $member) {
        if ($member->isActive()) { // error: Use guard clauses
            processMember($member);
        }
    }
}

// Complex condition - still only if in loop
foreach ($records as $record) {
    if ($record->status === 'active' && $record->type === 'premium' && $record->balance > 0) { // error: Use guard clauses
        processRecord($record);
    }
}