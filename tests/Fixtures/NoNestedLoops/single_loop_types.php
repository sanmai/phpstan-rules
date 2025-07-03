<?php

declare(strict_types=1);

namespace TestFixtures\NoNestedLoops;

// Test individual loop types to ensure each instanceof check is tested
class SingleLoopTypes
{
    public function testSingleFor($items)
    {
        // Just a for loop - should not trigger
        for ($i = 0; $i < 10; $i++) {
            echo $i;
        }
    }

    public function testSingleForeach($items)
    {
        // Just a foreach loop - should not trigger
        foreach ($items as $item) {
            echo $item;
        }
    }

    public function testSingleWhile($items)
    {
        // Just a while loop - should not trigger
        $i = 0;
        while ($i < 10) {
            echo $i;
            $i++;
        }
    }

    public function testSingleDo($items)
    {
        // Just a do-while loop - should not trigger
        $i = 0;
        do {
            echo $i;
            $i++;
        } while ($i < 10);
    }

    // Test that non-loop nodes don't trigger the rule
    public function testNonLoopNode($condition)
    {
        if ($condition) {
            echo "Not a loop";
        }
        
        switch ($condition) {
            case 1:
                echo "Still not a loop";
                break;
        }
    }
}