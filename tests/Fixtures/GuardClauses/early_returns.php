<?php

declare(strict_types=1);

namespace TestFixtures\GuardClauses;

use Exception;

// Mock functions
function processItem($item) {}
function handleItem($item) {}
function validateData($data)
{
    return true;
}

class EarlyReturnsTest
{
    public function testBreak($items)
    {
        // Good - contains only break
        foreach ($items as $item) {
            if ($item['skip']) {
                break;
            }
            processItem($item);
        }
    }

    public function testReturn($items)
    {
        // Good - contains only return
        foreach ($items as $item) {
            if ($item['stop']) {
                return;
            }
            processItem($item);
        }
    }

    public function testThrow($items)
    {
        // Good - contains only throw
        foreach ($items as $item) {
            if ($item['error']) {
                throw new Exception('Error');
            }
            processItem($item);
        }
    }

    public function testExit($items)
    {
        // Good - contains only exit
        foreach ($items as $item) {
            if ($item['fatal']) {
                exit(1);
            }
            processItem($item);
        }
    }

    public function testDie($items)
    {
        // Good - contains only die
        foreach ($items as $item) {
            if ($item['fatal']) {
                die('Fatal error');
            }
            processItem($item);
        }
    }

    public function testMixedEarlyReturns($items)
    {
        // Good - contains multiple early returns
        foreach ($items as $item) {
            if ($item['condition']) {
                if ($item['break']) {
                    break;
                }
                continue;
            }
            processItem($item);
        }
    }

    public function testNonEarlyReturn($items)
    {
        // Bad - contains non-early return statement
        foreach ($items as $item) {
            if ($item['process']) { // error: Use guard clauses
                handleItem($item);
            }
            processItem($item);
        }
    }

    public function testEmptyIfBody($items)
    {
        // Bad - empty if body is not an early return
        foreach ($items as $item) {
            if ($item['skip']) { // error: Use guard clauses
                // Do nothing
            }
            processItem($item);
        }
    }

    public function testComplexCondition($data)
    {
        // Bad - complex condition that should be guard clause
        while ($row = $data->fetch()) {
            if ($row['active'] && $row['valid'] && validateData($row)) { // error: Use guard clauses
                processItem($row);
            }
            // More code after the if
            $data->next();
        }
    }
}
