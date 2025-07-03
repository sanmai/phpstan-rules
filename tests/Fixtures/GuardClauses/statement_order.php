<?php

declare(strict_types=1);

namespace TestFixtures\GuardClauses;

// Test to ensure statement order is properly checked
class StatementOrderTest
{
    public function testEarlyReturnsOrder($items)
    {
        // All of these SHOULD trigger the rule because they only contain early returns
        // and there's code after them (processItem)
        foreach ($items as $item) {
            if ($item['condition1']) { // error: Use guard clauses
                return; // Early return first
            }
            
            if ($item['condition2']) { // error: Use guard clauses
                continue; // Continue second
            }
            
            if ($item['condition3']) { // error: Use guard clauses
                break; // Break third
            }
            
            if ($item['condition4']) { // error: Use guard clauses
                throw new \Exception('Error'); // Throw fourth
            }
            
            if ($item['condition5']) { // error: Use guard clauses
                exit(1); // Exit fifth
            }
            
            if ($item['condition6']) { // error: Use guard clauses
                die('Fatal'); // Die sixth
            }
            
            processItem($item);
        }
    }
    
    public function testMixedStatements($items)
    {
        // Bad - contains non-early return statement mixed with early returns
        foreach ($items as $item) {
            if ($item['condition']) { // error: Use guard clauses
                continue; // This is an early return
                echo "unreachable"; // But this would be unreachable anyway
            }
            processItem($item);
        }
    }
    
    public function testEmptyExpressionStatement($items)
    {
        // Bad - expression statement that isn't an early return
        foreach ($items as $item) {
            if ($item['condition']) { // error: Use guard clauses
                $item['processed'] = true; // This is an expression but not early return
            }
            processItem($item);
        }
    }
    
    public function testFunctionCallExpression($items)
    {
        // Bad - function call expression that isn't an early return
        foreach ($items as $item) {
            if ($item['condition']) { // error: Use guard clauses
                processItem($item); // Function call expression
            }
            logItem($item);
        }
    }
    
    public function testLastStatementPosition($items)
    {
        // Test position checking - last statement with single expression is OK
        foreach ($items as $item) {
            processItem($item);
            logItem($item);
            if ($item['special']) { // No error - last statement
                markSpecial($item);
            }
        }
    }
    
    public function testNotLastStatement($items)
    {
        // Bad - not the last statement
        foreach ($items as $item) {
            processItem($item);
            if ($item['special']) { // error: Use guard clauses
                markSpecial($item);
            }
            logItem($item); // There's a statement after the if
        }
    }
}

// Mock functions
function processItem($item) {}
function logItem($item) {}
function markSpecial($item) {}