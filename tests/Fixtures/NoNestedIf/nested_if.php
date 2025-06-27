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

// This file contains examples that should trigger the NoNestedIfStatementsRule

// Simple nested if - should trigger
if ($condition1) {
    if ($condition2) { // error: Nested if statements should be avoided.
        doSomething();
    }
}

// Nested if with multiple statements in outer if - should NOT trigger
if ($condition1) {
    doSomethingFirst();
    if ($condition2) { // No error - multiple statements in parent
        doSomething();
    }
}

// If with else - should NOT trigger
if ($condition1) {
    if ($condition2) { // No error - parent has else
        doSomething();
    }
} else {
    doSomethingElse();
}

// If with elseif - should NOT trigger
if ($condition1) {
    if ($condition2) { // No error - parent has elseif
        doSomething();
    }
} elseif ($condition3) {
    doSomethingElse();
}

// Nested if where inner has else - should NOT trigger
if ($condition1) {
    if ($condition2) { // No error - inner has else
        doSomething();
    } else {
        doSomethingElse();
    }
}

// Another simple nested if that should trigger
function example($a, $b)
{
    if ($a > 0) {
        if ($b > 0) { // error: Nested if statements should be avoided.
            return $a + $b;
        }
    }
    return 0;
}
