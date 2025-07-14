<?php

/**
 * Test cases with non-function-call comparisons that should not trigger the rule
 */

$array = [1, 2, 3];

// Variable comparison - not a function call
if ($array === 0) {
    echo "This is not count()";
}

// Property access - not a function call  
class TestClass {
    public $count = 0;
}

$obj = new TestClass();
if ($obj->count === 0) {
    echo "Property access, not count()";
}

// Method call with wrong name - is a function call but not count()
if ($obj->getCount() === 0) {
    echo "Different method name";
}

// Actual count comparison that should be flagged
if (count($array) === 0) {
    echo "This should be flagged";
}