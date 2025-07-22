<?php

declare(strict_types=1);

namespace Sanmai\PHPStanRules\Tests\Fixtures\NoNestedLoopsRule;

class TryCatchNestedLoops
{
    public function exampleFromIssue25(): array
    {
        $that = true;
        $foo = [1, 2, 3];
        $baz = 2;

        if ($that) {
            try {
                foreach ($foo as $bar) { // This should NOT be flagged - only one loop
                    if ($bar === $baz) {
                        return [];
                    }
                }
            } catch (\Throwable) {
                // Empty catch
            }
        }

        return [];
    }

    public function anotherLoop(): void
    {
        foreach ($items as $item) {
            try {
                foreach ($item->getSubItems() as $subItem) { // Should be flagged - nested loops
                    $subItem->process();
                }
            } catch (\Exception $e) {
                // Handle exception
            }
        }
    }

    public function singleLoopInTry(): void
    {
        try {
            foreach ($items as $item) { // Should NOT be flagged - not nested
                $item->process();
            }
        } catch (\Exception $e) {
            // Handle exception
        }
    }
}