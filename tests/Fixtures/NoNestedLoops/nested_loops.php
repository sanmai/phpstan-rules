<?php

/**
 * Copyright 2025 Alexey Kopytko <alexey@kopytko.com>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

declare(strict_types=1);

namespace TestFixtures\NoNestedLoops;

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
