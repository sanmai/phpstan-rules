<?php

declare(strict_types=1);

namespace TestFixtures\GuardClauses;

// Mock functions
function doSomething() {}
function doMore() {}

class EdgeCases
{
    public function testEmptyLoop($items)
    {
        // Empty loop - no errors
        foreach ($items as $item) {
            // Empty
        }
    }

    public function testOnlyIfInLoop($items)
    {
        // Loop with only an if statement
        foreach ($items as $item) {
            if ($item) { // error: Use guard clauses
                doSomething();
            }
        }
    }

    public function testIfAsLastStatement($items)
    {
        // If as the last statement in loop
        foreach ($items as $item) {
            doSomething();
            if ($item) { // No error - last statement
                doMore();
            }
        }
    }

    public function testMultipleIfsInLoop($items)
    {
        // Multiple if statements
        foreach ($items as $item) {
            if ($item['first']) { // error: Use guard clauses
                doSomething();
            }

            if ($item['second']) { // error: Use guard clauses
                doMore();
            }

            // More code after ifs
            $item['processed'] = true;
        }
    }

    public function testNestedLoopsWithGuardClauses($matrix)
    {
        // Nested loops both with guard clauses
        foreach ($matrix as $row) {
            if (!$row) {
                continue;
            }

            foreach ($row as $cell) {
                if (!$cell) {
                    continue;
                }
                doSomething();
            }
        }
    }

    public function testIfWithMultipleConditions($items)
    {
        // If with && conditions
        foreach ($items as $item) {
            if ($item['active'] && $item['valid']) { // error: Use guard clauses
                doSomething();
            }
            doMore();
        }
    }

    public function testIfWithOrConditions($items)
    {
        // If with || conditions
        foreach ($items as $item) {
            if ($item['skip'] || $item['ignore']) { // Good - contains continue
                continue;
            }
            doSomething();
        }
    }
}
