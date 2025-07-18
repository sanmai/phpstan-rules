<?php

class FooBar
{
    public function __construct(private readonly bool $do)
    {

    }

    public function bad(): void
    {
        $this->work();

        if ($this->do) { // Error: use guard clauses
            $this->work('1');
            $this->work('2');
            $this->work('3');
        }
    }

    public function also_bad(): void
    {
        $this->work();

        if ($this->do) { // Error: use guard clauses (no return type can be void)
            $this->work('1');
            $this->work('2');
            $this->work('3');
        }
    }

    public function good(): void
    {
        $this->work();

        if (!$this->do) {
            return;
        }

        $this->work('1');
        $this->work('2');
        $this->work('3');
    }

    public function neverReturn(): never
    {
        $this->work();

        if ($this->do) { // Not an error, since we can't have return here
            $this->work('1');
            $this->work('2');
            $this->work('3');
        }
    }

    public function specificArray(): array
    {
        $this->work();

        if ($this->do) { // Not an error, since we return something else after
            $this->work('1');
            $this->work('2');
            $this->work('3');

            return ['2'];
        }

        return ['1']; // Could be nothing here; we do not need to check
    }

    public function specificString(): string
    {
        $this->work();

        if ($this->do) { // Not an error, since we return something else after
            $this->work('1');
            $this->work('2');
            $this->work('3');

            return '2';
        }
        // nothing here, but not our problem
    }

    public function work($arg = null): void
    {

    }
}

function bad($that): void
{
    $that->work();

    if ($that->do) { // Error: use guard clauses
        $that->work('1');
        $that->work('2');
        $that->work('3');
    }
}

// Function with non-void return type (should not trigger)
function hasStringReturn(): string
{
    $result = doSetup();
    
    if ($result->isValid()) {
        $result->process();
        return $result->getValue();
    }
    
    return 'default';
}
