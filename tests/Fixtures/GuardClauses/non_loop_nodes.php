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
        }
        break;
}

// Function with if - not a loop
function myFunction() {
    if ($condition) {
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