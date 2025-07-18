<?php

declare(strict_types=1);

// Functions with class return types that end with if statements
// These should NOT trigger the rule because they have non-void return types

class Example {}

function returnsClass(): Example
{
    $this->setup();
    
    if ($this->condition) {
        $this->process();
    }
}

function returnsStdClass(): stdClass  
{
    if ($this->condition) {
        $this->process();
    }
}

function returnsDateTime(): DateTime
{
    if ($this->condition) {
        $this->process();
    }
}