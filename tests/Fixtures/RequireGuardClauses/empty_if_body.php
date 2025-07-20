<?php

/**
 * Test cases with empty if statement bodies
 */

// For loop with truly empty if body (no comments)
for ($i = 0; $i < 10; $i++) {
    if ($i > 5) {
    }
}

// Foreach with empty if body
foreach ($items as $item) {
    if ($item > 5) {
    }
}

// While loop with empty if body
$i = 0;
while ($i < 10) {
    if ($i > 5) {
    }
    $i++;
}

// Do-while with empty if body
$i = 0;
do {
    if ($i > 5) {
    }
} while ($i++ < 10);

// For loop with if body containing only comment (creates Nop statement)
for ($i = 0; $i < 10; $i++) {
    if ($i > 5) {
        // Just a comment
    }
}

foreach ($items as $item) {
    if ($item->baz() > 5) {
        foreach ($item->foo() as $bar) {
            // Bad
        }
    }
}

foreach ($items as $item) {
    if ($item->baz() > 5) {
        while ($item->foo()) {
            // Bad
        }
    }
}

foreach ($items as $item) {
    if ($item->baz() > 5) {
        do {
            // Bad
        } while ($item->foo());
    }
}
