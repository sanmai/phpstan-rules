<?php

declare(strict_types=1);

namespace TestFixtures\GuardClauses;

// Test that if with elseif doesn't prevent checking other statements
class ElseIfWithMultipleStatementsTest
{
    public function testElseIfDoesNotStopChecking($items)
    {
        // The first if has elseif, so it's OK
        // But the second if should still be checked
        foreach ($items as $item) {
            // This one has elseif, so it's OK
            if ($item['hasElseIf']) { // No error - has elseif
                processItem($item);
            } elseif ($item['other']) {
                otherAction($item);
            }
            
            // This one should be flagged as needing guard clause
            if ($item['needsGuard']) { // error: Use guard clauses
                handleItem($item);
            }
            
            // More statements after
            logItem($item);
        }
    }
}

// Mock functions
function processItem($item) {}
function otherAction($item) {}
function handleItem($item) {}
function logItem($item) {}