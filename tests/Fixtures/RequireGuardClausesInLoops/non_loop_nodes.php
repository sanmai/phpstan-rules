<?php

declare(strict_types=1);

// Test to ensure non-loop nodes are not processed

// Regular if statement - not in a loop
if ($condition) {
    doSomething();
}

// Switch statement - not a loop
switch ($value) {
    case 1:
        if ($condition) {
            doSomething();
            doSomethingElse();
        }
        break;
}

// Function with if - not a loop
function myFunction($condition) {
    if ($condition) {
        doSomething();
        return true;
    }
}

// Class method with if - not a loop
class MyClass {
    public function method() {
        if ($this->condition) {
            $this->doSomething();
        }
    }
}

// Try-catch block - not a loop
try {
    if ($condition) {
        throw new \Exception('Error occurred');
    }
} catch (\Exception $e) {
    handleError($e);
}

if ($anotherCondition) {
    // This is just a regular if statement, not in a loop
    doSomethingElse();
    // Comments should not affect the rule
    doThingDifferent();
    // More comments to ensure the rule does not flag this
}
