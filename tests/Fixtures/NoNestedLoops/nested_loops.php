<?php

declare(strict_types=1);

namespace TestFixtures\NoNestedLoops;

use function array_map;

// This file contains examples that should trigger the NoNestedLoopsRule

// Direct nested foreach
foreach ($items as $item) {
    foreach ($item['subitems'] as $subitem) { // error: Nested loops are not allowed.
        echo $subitem;
    }
}

// Direct nested for loop
for ($i = 0; $i < 10; $i++) {
    for ($j = 0; $j < 10; $j++) { // error: Nested loops are not allowed.
        echo $i * $j;
    }
}

// Direct nested while
$i = 0;
while ($i < 10) {
    $j = 0;
    while ($j < 10) { // error: Nested loops are not allowed.
        echo $i * $j;
        $j++;
    }
    $i++;
}

// Direct nested do-while
$i = 0;
do {
    $j = 0;
    do { // error: Nested loops are not allowed.
        echo $i * $j;
        $j++;
    } while ($j < 10);
    $i++;
} while ($i < 10);

// Mixed nested loops - foreach with do-while
foreach ($items as $item) {
    $i = 0;
    do { // error: Nested loops are not allowed.
        echo $item . $i;
        $i++;
    } while ($i < 5);
}

// This should NOT trigger - function boundary
foreach ($items as $item) {
    $result = array_map(function ($subitem) {
        foreach ($subitem['data'] as $data) { // No error - inside function boundary
            echo $data;
        }
    }, $item['subitems']);
}

// This should NOT trigger - method call
class Example
{
    public function process($items)
    {
        foreach ($items as $item) {
            $this->processSubitems($item['subitems']);
        }
    }

    private function processSubitems($subitems)
    {
        foreach ($subitems as $subitem) { // No error - different method
            echo $subitem;
        }
    }
}
