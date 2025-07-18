<?php

declare(strict_types=1);

// Functions with non-void return types that end with if statements
// These should NOT trigger the rule

function stringReturn(): string
{
    $result = '';
    
    if ($condition) {
        $result = 'yes';
    }
    
    return $result;
}

function intReturn(): int
{
    $value = 0;
    
    if ($condition) {
        $value = 1;
    }
    
    return $value;
}

// This function with string return type ends with an if statement
// It should NOT trigger the rule because it has a return type
function endingWithIf(): string
{
    $this->prepare();
    
    if ($this->condition) {
        $this->process();
        $this->result = 'done';
    }
}

// Functions with void return types that end with if statements
// These SHOULD trigger the rule

function voidReturn(): void
{
    if ($condition) { // Error: should use guard clause
        doSomething();
    }
}

function noReturn()
{
    if ($condition) { // Error: should use guard clause
        doSomething();
    }
}