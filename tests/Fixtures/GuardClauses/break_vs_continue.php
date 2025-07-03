<?php

declare(strict_types=1);

namespace TestFixtures\GuardClauses;

// Test to ensure break vs continue mutations are caught
class BreakVsContinueTest
{
    public function testMultipleEarlyReturnIfStatements($items)
    {
        // Multiple if statements that only contain early returns
        // These should not be flagged because they contain only early returns
        foreach ($items as $item) {
            // These are NOT flagged because containsOnlyEarlyReturns returns true
            // The rule recognizes these as already following the early return pattern
            if ($item['doReturn']) { // No error - contains only early return
                return;
            }

            if ($item['doContinue']) { // No error - contains only early return
                continue;
            }

            if ($item['doBreak']) { // No error - contains only early return
                break;
            }

            // If we have multiple ifs with early returns and there's processing after,
            // the rule should not flag them because they contain only early returns
            processItem($item);

            if ($item['moreBreak']) {
                break;
            }
        }
    }

    public function testBreakVsContinueBehavior($items)
    {
        // Test that ensures the difference between break and continue matters
        foreach ($items as $index => $item) {
            // But wait, these SHOULD also not be flagged for same reason!
            if ($index === 0) { // No error? - contains only early return
                continue; // Skip to next iteration
            }

            if ($index === 1) { // No error? - contains only early return
                break; // Exit the loop entirely
            }

            // This one has processing, so it should be flagged
            if ($index === 2) { // error: Use guard clauses
                processItem($item);
            }

            // More processing
            logItem($item);
        }
    }
}

// Mock functions
function processItem($item) {}
function logItem($item) {}
