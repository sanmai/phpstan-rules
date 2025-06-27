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

declare(strict_types=1);

namespace TestFixtures;

use function count;

// Mock functions
function processItem($item) {}
function logItem($item) {}
function validateItem($item) {}
function saveItem($item) {}
function skipItem($item) {}
function markSpecial($item) {}
function updateRow($row) {}
function notifyUpdate($row) {}
function fetchRow()
{
    return ['status' => 'active'];
}
function process($item) {}

class GuardClauseExamples
{
    public function example1($items)
    {
        // If statement with code after it - should use guard clause
        foreach ($items as $item) {
            if ($item['active']) { // error: Use guard clauses
                processItem($item);
            }
            logItem($item); // Statement after if
        }
    }

    public function example2($items)
    {
        // If statement with multiple statements inside - should use guard clause
        foreach ($items as $item) {
            if ($item['valid']) { // error: Use guard clauses
                validateItem($item);
                processItem($item);
                saveItem($item);
            }
        }
    }

    public function example3($items)
    {
        // Good - using guard clause
        foreach ($items as $item) {
            if (!$item['active']) { // No error - contains early return
                continue;
            }
            processItem($item);
        }
    }

    public function example4($items)
    {
        // Good - if with else
        foreach ($items as $item) {
            if ($item['active']) { // No error - has else branch
                processItem($item);
            } else {
                skipItem($item);
            }
        }
    }

    public function example5($items)
    {
        // Good - single statement in if at end of loop
        foreach ($items as $item) {
            logItem($item);
            if ($item['special']) { // No error - last statement with single action
                markSpecial($item);
            }
        }
    }

    public function example6()
    {
        // Bad - single if but with multiple statements inside
        while ($row = fetchRow()) {
            if ('active' === $row['status']) { // error: Use guard clauses
                updateRow($row);
                notifyUpdate($row);
            }
        }
    }

    public function example7($items)
    {
        // Good - if contains only early returns
        for ($i = 0; $i < count($items); $i++) {
            if (null === $items[$i]) { // No error - contains only early return
                continue;
            }
            process($items[$i]);
        }
    }
}
