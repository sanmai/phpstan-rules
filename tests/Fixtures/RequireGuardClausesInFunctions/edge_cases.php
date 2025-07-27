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

    if ($this->condition) { // Error: flagged because our other rule forbids else entirely
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

// Edge case: abstract method (should not trigger - no body)
abstract class AbstractExample
{
    abstract public function abstractMethod(): void;

    // Interface methods also have no body
    public function concreteMethod(): void
    {
        if ($this->condition) { // Error: should use guard clause
            $this->work();
        }
    }
}

function itThrows(): void
{
    if (bad()) {
        throw new Exception();
    }
}

function multipleStatementsWithThrow(): void
{
    if (bad()) { // Error: should use guard clause (multiple statements including throw)
        $this->cleanup();
        throw new Exception();
    }
}

function singleReturnStatement(): void
{
    if (bad()) { // Error: should use guard clause (single return, not throw)
        return;
    }
}

function emptyIfStatement(): void
{
    if (bad()) { // Error: should use guard clause (empty if block)
    }
}

function throwThenCleanup(): void
{
    if (bad()) { // OK: starts with throw, so no guard clause needed (unreachable code after throw)
        throw new Exception();
        $this->cleanup(); // This is unreachable code after throw
    }
}
