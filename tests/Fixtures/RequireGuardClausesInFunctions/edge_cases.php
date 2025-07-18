<?php

declare(strict_types=1);

// Edge case: function with single if statement (should trigger - use guard clause)
function singleStatement(): void
{
    if ($this->condition) { // Error: should use guard clause
        $this->doWork();
    }
}

// Edge case: function with no body
function emptyBody(): void
{
}

// Edge case: function with exactly one statement (should not trigger)
function oneStatement(): void
{
    $this->doSomething();
}

// Edge case: function with only if statement (should trigger - perfect case for guard clause)
function onlyIfStatement(): void
{
    if ($this->condition) { // Error: should use guard clause
        $this->work1();
        $this->work2();
    }
}

// Edge case: if with single statement (should trigger - any final if should be guard clause)
function ifWithSingleStatement(): void
{
    $this->setup();

    if ($this->condition) { // Error: should use guard clause
        $this->singleWork();
    }
}

// Edge case: no return type (should trigger)
function noReturnType()
{
    $this->setup();

    if ($this->condition) { // Error: should use guard clause
        $this->work1();
        $this->work2();
    }
}

// Edge case: if with else (should not trigger)
function withElse(): void
{
    $this->setup();

    if ($this->condition) {
        $this->work1();
        $this->work2();
    } else {
        $this->alternative();
    }
}

// Edge case: if with elseif (should not trigger)
function withElseIf(): void
{
    $this->setup();

    if ($this->condition) {
        $this->work1();
        $this->work2();
    } elseif ($this->other) {
        $this->alternative();
    }
}
