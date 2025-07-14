<?php

/**
 * Specific test for do-while loops
 */

// Do-while with only if (should be flagged)
$i = 0;
do {
    if ($i > 5) {
        echo "value: $i";
        echo "second statement";
    }
} while ($i++ < 10);