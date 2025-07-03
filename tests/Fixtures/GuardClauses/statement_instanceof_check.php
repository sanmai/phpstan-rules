<?php

declare(strict_types=1);

namespace TestFixtures\GuardClauses;

// Specific test to ensure instanceof Expression check is necessary
class StatementInstanceofCheckTest
{
    public function testExpressionVsNonExpression($items)
    {
        foreach ($items as $item) {
            // This if contains only Expression statements that are NOT early returns
            if ($item['expressions']) { // error: Use guard clauses
                // These are all Expression statements
                $x = 1;
                func();
                $item->method();
                
                // But none of them are early returns (throw, exit, die)
                // So the if should be flagged
            }
            
            logItem($item);
        }
    }
    
    public function testNonExpressionEarlyReturn($items)
    {
        foreach ($items as $item) {
            // This contains a non-Expression statement, so containsOnlyEarlyReturns
            // should return false when it encounters the echo statement
            if ($item['nonExpression']) { // error: Use guard clauses
                echo "This is not an Expression node";
                // Even if we had an early return after this, the echo makes it invalid
                return;
            }
            
            processItem($item);
        }
    }
    
    public function testOnlyExpressionEarlyReturns($items)
    {
        foreach ($items as $item) {
            // These ARE flagged because even though they contain only early returns,
            // there's code after them (processItem). The rule wants guard clauses.
            if ($item['error']) { // error: Use guard clauses
                throw new \Exception(); // Early return but should use guard clause
            }
            
            if ($item['fatal']) { // error: Use guard clauses
                exit(1); // Early return but should use guard clause
            }
            
            if ($item['die']) { // error: Use guard clauses
                die(); // Early return but should use guard clause
            }
            
            processItem($item);
        }
    }
}

// Mock functions
function logItem($item) {}
function processItem($item) {}
function func() {}