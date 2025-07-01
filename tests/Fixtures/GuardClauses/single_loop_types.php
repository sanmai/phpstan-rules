<?php

declare(strict_types=1);

namespace TestFixtures\GuardClauses;

// Test individual loop types for guard clause rule
class SingleLoopTypesGuard
{
    public function testForLoop()
    {
        // Test guard clause in for loop
        for ($i = 0; $i < 10; $i++) {
            if ($i % 2 === 0) { // error: Use guard clauses
                echo $i;
            }
            // More code after if
            $result = $i * 2;
        }
    }

    public function testForeachLoop($items)
    {
        // Test guard clause in foreach loop
        foreach ($items as $item) {
            if ($item['active']) { // error: Use guard clauses
                processItem($item);
            }
            // More code after if
            logItem($item);
        }
    }

    public function testWhileLoop()
    {
        // Test guard clause in while loop
        $i = 0;
        while ($i < 10) {
            if ($i > 5) { // error: Use guard clauses
                echo $i;
            }
            // More code after if
            $i++;
        }
    }

    public function testDoWhileLoop()
    {
        // Test guard clause in do-while loop
        $i = 0;
        do {
            if ($i < 5) { // error: Use guard clauses
                echo $i;
            }
            // More code after if
            $i++;
        } while ($i < 10);
    }

    // Test non-loop nodes are ignored
    public function testNonLoop($condition)
    {
        if ($condition) {
            echo "Not in a loop";
        }
        echo "After if";
    }
}

// Mock functions
function processItem($item) {}
function logItem($item) {}