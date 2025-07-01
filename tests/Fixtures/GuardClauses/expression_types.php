<?php

declare(strict_types=1);

namespace TestFixtures\GuardClauses;

// Test different statement types to ensure Expression check works
class ExpressionTypesTest
{
    public function testPureExpressionStatements($items)
    {
        foreach ($items as $item) {
            // This if contains only expression statements (that are not early returns)
            if ($item['condition']) { // error: Use guard clauses
                $x = 1; // Assignment expression
                $item['processed'] = true; // Array access expression
                processItem($item); // Function call expression
                $item->method(); // Method call expression
                new \stdClass(); // New expression
            }
            
            logItem($item);
        }
    }
    
    public function testNonExpressionStatementsOnly($items)
    {
        foreach ($items as $item) {
            // This if contains only non-expression statements
            if ($item['condition']) { // error: Use guard clauses
                // Echo is a statement, not an expression
                echo "Processing";
                
                // Declare is a statement, not an expression
                declare(ticks=1) {
                    $x = 1;
                }
            }
            
            processItem($item);
        }
    }
    
    public function testMixedStatementTypes($items)
    {
        foreach ($items as $item) {
            // Mix of expression and non-expression statements
            if ($item['condition']) { // error: Use guard clauses
                echo "Start"; // Non-expression statement
                processItem($item); // Expression statement
                global $counter; // Non-expression statement
                $counter++; // Expression statement
            }
            
            finalizeItem($item);
        }
    }
}

// Mock functions
function processItem($item) {}
function logItem($item) {}
function finalizeItem($item) {}