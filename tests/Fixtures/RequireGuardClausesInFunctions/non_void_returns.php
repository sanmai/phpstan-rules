<?php

declare(strict_types=1);

// All of these should NOT trigger the rule because they have non-void return types

function returnsString(): string
{
    $result = doSetup();
    
    if ($result->isValid()) {
        $result->process();
        return $result->getValue();
    }
    
    return 'default';
}

function returnsInt(): int
{
    if ($this->condition) {
        return 42;
    }
    
    return 0;
}

function returnsArray(): array
{
    $data = [];
    
    if ($this->hasData()) {
        $data = $this->getData();
        $data = array_map('trim', $data);
    }
    
    return $data;
}

function returnsBool(): bool
{
    $this->setup();
    
    if ($this->isConfigured()) {
        $this->validate();
        return true;
    }
    
    return false;
}

class Example
{
    public function returnsObject(): object
    {
        if ($this->hasCache()) {
            return $this->getFromCache();
        }
        
        return new stdClass();
    }
    
    public function returnsNullable(): ?string
    {
        if ($this->hasValue()) {
            return $this->getValue();
        }
        
        return null;
    }
    
    public function returnsUnion(): string|int
    {
        if ($this->isNumeric()) {
            return 42;
        }
        
        return 'forty-two';
    }
    
    // This SHOULD trigger the rule - void return type
    public function voidMethodWithIf(): void
    {
        $this->setup();
        
        if ($this->condition) { // Error: should use guard clause
            $this->work();
        }
    }
}