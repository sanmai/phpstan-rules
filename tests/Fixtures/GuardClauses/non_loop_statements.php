<?php

/**
 * Test that non-loop statements don't trigger the rule
 * This should improve code coverage for getLoopStatements returning null
 */

// Regular if statement - not a loop
if (true) {
    if (false) {
        echo "not in a loop";
    }
}

// Switch statement - not a loop
switch ($var) {
    case 1:
        if (true) {
            echo "in switch";
        }
        break;
}

// Try-catch - not a loop
try {
    if (true) {
        echo "in try";
    }
} catch (Exception $e) {
    if (true) {
        echo "in catch";
    }
}

// Function declaration - not a loop
function testFunction() {
    if (true) {
        echo "in function";
    }
}

// Class declaration - not a loop
class TestClass {
    public function method() {
        if (true) {
            echo "in method";
        }
    }
}