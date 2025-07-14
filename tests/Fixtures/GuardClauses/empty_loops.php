<?php

/**
 * Test loops with empty bodies
 */

// Empty foreach
foreach ([1, 2, 3] as $item) {
    // Empty body
}

// Empty while
while (false) {
    // Empty body
}

// Empty for
for ($i = 0; $i < 0; $i++) {
    // Empty body
}

// Empty do-while
do {
    // Empty body
} while (false);