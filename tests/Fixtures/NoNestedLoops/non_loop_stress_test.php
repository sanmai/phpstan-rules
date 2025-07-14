<?php

/**
 * Comprehensive test of non-loop nodes to verify isLoopNode check
 */

// Classes, interfaces, traits - not loops
class SomeClass {}
interface SomeInterface {}
trait SomeTrait {}

// Functions and methods - not loops
function globalFunction() {
    return "not a loop";
}

// Variables and assignments - not loops
$variable = "assignment";
$array = [1, 2, 3];

// Control structures that are NOT loops
if (true) {
    echo "if statement";
}

switch ($variable) {
    case "test":
        break;
}

try {
    // Exception handling
} catch (Exception $e) {
    // Error handling
}

// Actual nested loop that SHOULD be flagged
foreach ($array as $item) {
    foreach ($array as $nested) {
        echo $item . $nested;
    }
}