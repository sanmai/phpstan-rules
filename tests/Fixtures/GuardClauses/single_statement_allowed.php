<?php

/**
 * Test cases where loops contain only a single if statement
 * These should be allowed by the rule
 */

// For loop with single-statement if
for ($i = 0; $i < 10; $i++) {
    if ($i > 5) {
        echo "value: $i";
    }
}

// Foreach with single-statement if  
foreach ($items as $item) {
    if ($item > 5) {
        echo "value: $item";
    }
}

// While loop with single-statement if
$i = 0;
while ($i < 10) {
    if ($i > 5) {
        echo "value: $i";
    }
    $i++;
}

// Do-while with single-statement if
$i = 0;
do {
    if ($i > 5) {
        echo "value: $i";
    }
} while ($i++ < 10);