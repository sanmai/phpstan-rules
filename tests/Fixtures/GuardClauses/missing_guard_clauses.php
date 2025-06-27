<?php
/**
 * Copyright 2025 Alexey Kopytko <alexey@kopytko.com>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

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
