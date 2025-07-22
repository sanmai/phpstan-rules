<?php

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

// If with else - now triggers (else no longer allowed)
if ($condition1) {
    if ($condition2) { // error: Nested if statements should be avoided.
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

// Nested if where inner has else - now triggers (else no longer allowed)
if ($condition1) {
    if ($condition2) { // error: Nested if statements should be avoided.
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

// Example with else and elseif
if ($condition1) {
    if ($condition2) { // error: Nested if statements should be avoided.
        doSomething();
    } elseif ($condition3) {
        doSomethingElseIf();
    } else {
        doSomethingElse();
    }
}
