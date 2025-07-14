<?php

/**
 * Edge cases to test yield detection logic specifically
 */

function testYieldDetection() {
    // This should be flagged - loop with only if containing multiple statements
    foreach ([1, 2, 3] as $item) {
        if ($item > 1) {
            yield $item;
            echo "after yield";
        }
    }
}

function testReturnStatements() {
    // This should NOT be flagged due to return statement (not Expression)
    foreach ([1, 2, 3] as $item) {
        if ($item > 1) {
            return $item; // Return statement, not Expression
        }
        echo "unreachable";
    }
}

function testMixedWithNonLoops() {
    // Non-loop construct - should not be processed at all
    if (true) {
        echo "This is not a loop";
        echo "Multiple statements but not in a loop";
    }
    
    // This IS a loop and should be flagged
    foreach ([1, 2, 3] as $item) {
        if ($item > 1) {
            echo $item;
            print "second statement";
        }
    }
}