<?php

declare(strict_types=1);

namespace TestFixtures\GuardClauses;

// Test to ensure continue vs break mutation is caught when processing multiple if statements
class MultipleEarlyReturnIfsTest
{
    public function testContinueVsBreakInRuleProcessing($items)
    {
        // This test ensures that the rule processes ALL if statements in a loop,
        // not just the first one (which would happen if continue was changed to break)
        foreach ($items as $item) {
            // First if with only early return - should not be flagged
            if ($item['skipFirst']) {
                continue;
            }
            
            // Second if with only early return - should not be flagged
            if ($item['skipSecond']) {
                break;
            }
            
            // Third if with processing - SHOULD be flagged
            if ($item['processThird']) { // error: Use guard clauses
                processItem($item);
            }
            
            // Fourth if with processing - SHOULD also be flagged
            // This tests that the rule continues processing after finding early return ifs
            if ($item['processFourth']) { // error: Use guard clauses
                handleItem($item);
            }
            
            // Final processing
            finalizeItem($item);
        }
    }
    
    public function testAllEarlyReturnsNoFlags($items)
    {
        // All ifs contain only early returns, none should be flagged
        foreach ($items as $item) {
            if ($item['a']) {
                continue;
            }
            
            if ($item['b']) {
                break;
            }
            
            if ($item['c']) {
                return;
            }
            
            if ($item['d']) {
                throw new \Exception();
            }
            
            processItem($item);
        }
    }
}

// Mock functions
function processItem($item) {}
function handleItem($item) {}
function finalizeItem($item) {}