<?php

declare(strict_types=1);

namespace TestFixtures\GuardClauses;

// Test multiple if statements in loops to ensure continue vs break is handled correctly
class MultipleIfsTest
{
    public function testMultipleGuardableIfs($items)
    {
        // Multiple ifs that should all be flagged
        foreach ($items as $item) {
            if ($item['condition1']) { // error: Use guard clauses
                processItem($item);
            }
            
            if ($item['condition2']) { // error: Use guard clauses
                handleItem($item);
            }
            
            if ($item['condition3']) { // error: Use guard clauses
                validateItem($item);
            }
            
            // More code after all ifs
            logItem($item);
        }
    }
    
    public function testMixedIfTypes($items)
    {
        // Mix of guardable and non-guardable ifs
        foreach ($items as $item) {
            // This one has else, so it's OK
            if ($item['hasElse']) { // No error - has else
                processItem($item);
            } else {
                skipItem($item);
            }
            
            // This one should be guard clause
            if ($item['noElse']) { // error: Use guard clauses
                handleItem($item);
            }
            
            // This one has elseif, so it's OK
            if ($item['hasElseIf']) { // No error - has elseif
                validateItem($item);
            } elseif ($item['other']) {
                otherAction($item);
            }
            
            // This one only has early return, so it's OK
            if ($item['earlyReturn']) { // No error - contains only early return
                continue;
            }
            
            // Final action
            finalAction($item);
        }
    }
    
    public function testLastIfSpecialCase($items)
    {
        // Test that last if with single statement is OK
        foreach ($items as $item) {
            processItem($item);
            handleItem($item);
            
            // Last if with single statement - should be OK
            if ($item['special']) { // No error - last statement with single action
                markSpecial($item);
            }
        }
    }
    
    public function testEmptyLoop()
    {
        // Empty loop should not cause issues
        foreach ([] as $item) {
            // Nothing here
        }
        
        // Loop with only comments
        while (false) {
            // Just a comment
        }
    }
}

// Mock functions
function processItem($item) {}
function handleItem($item) {}
function validateItem($item) {}
function logItem($item) {}
function skipItem($item) {}
function otherAction($item) {}
function finalAction($item) {}
function markSpecial($item) {}