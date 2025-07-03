<?php

declare(strict_types=1);

namespace TestFixtures\GuardClauses;

// Specific test to ensure the instanceof Expression check is necessary
class MixedStatementTypesTest  
{
    public function testNonExpressionFollowedByEarlyReturn($items)
    {
        foreach ($items as $item) {
            // This if contains a non-Expression statement followed by an early return
            // The instanceof Expression check should catch the echo and return false
            if ($item['condition']) { // error: Use guard clauses
                echo "Processing"; // Non-Expression statement
                return; // Early return after non-expression
            }
            
            processItem($item);
        }
    }
    
    public function testOnlyNonExpressionStatements($items)
    {
        foreach ($items as $item) {
            // Only non-Expression statements
            if ($item['condition']) { // error: Use guard clauses
                echo "One";
                global $x;
                declare(ticks=1) {}
            }
            
            processItem($item);
        }
    }
    
    public function testExpressionThenNonExpression($items)
    {
        foreach ($items as $item) {
            // Expression followed by non-Expression
            if ($item['condition']) { // error: Use guard clauses
                processItem($item); // Expression
                echo "Done"; // Non-Expression
            }
            
            finalizeItem($item);
        }
    }
}

// Mock functions
function processItem($item) {}
function finalizeItem($item) {}