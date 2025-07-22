<?php

// Test edge cases that would break without proper guard clauses

// Empty if statement - would cause undefined offset without the count check
if ($condition) {
    // Completely empty
}

// If with multiple statements where first is not an if
if ($condition) {
    doSomething(); // Not an if
    if ($nested) { // This is the second statement
        doMore();
    }
}

// If with only non-if statement
if ($condition) {
    doSomething(); // Not an if statement
}

// Test with else block (should not be processed)
if ($condition) {
    if ($nested) {
        doSomething();
    }
} else {
    doElse();
}

// Test with elseif (should not be processed)
if ($condition) {
    if ($nested) {
        doSomething();
    }
} elseif ($other) {
    doElseIf();
}

// If with multiple statements including an if
if ($condition) {
    if ($nested) { // This is the second statement
        doMore();
    }
    if ($other) { // This is the second statement
        doMore();
    }
}

// Another edge case with nested if
if ($condition) {
    doStuff();
    if ($nested) {
        doSomething();
    }
}

// Not flagged
if ($condition) {
    if ($nested) {
        doMore();
    }
    if ($other) {
        doMore();
    }
    doSomething();
}

// Not flagged
if ($condition) {
    if ($nested) {
        doMore();
    }
    if ($other) {
        doMore();
    }
    if ($condition) {
        doSomething();
    }
}

// Not flagged
if ($condition) {
    $foo = bar();
    if ($nested) {
        doMore();
    }
    doSomething();
}
