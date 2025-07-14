<?php

/**
 * Test the policy constraint: should we flag nested ifs in multi-statement blocks?
 */

// Case 1: Current policy says NO - don't flag nested if when there are multiple statements
if ($condition1) {
    echo "first statement";
    if ($condition2) { // Currently NOT flagged due to multiple statements
        doSomething();
    }
    echo "third statement";
}

// Case 2: Current policy says YES - flag nested if when it's the only statement  
if ($condition3) {
    if ($condition4) { // Currently IS flagged - single statement
        doSomething();
    }
}

// Case 3: More complex multi-statement case
if ($condition5) {
    $var = "setup";
    if ($condition6) { // Should this be flagged?
        process($var);
    }
    cleanup();
}