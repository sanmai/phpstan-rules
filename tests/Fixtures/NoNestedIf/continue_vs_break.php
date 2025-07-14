<?php

/**
 * Test that the rule continues checking after finding an if with elseif
 */

// This tests the continue vs break mutation
if ($condition1) {
    // First statement is an if with elseif - should be skipped
    if ($condition2) {
        echo "do something";
    } elseif ($condition3) {
        echo "do something else";
    }
    
    // Second statement is a simple if - should be flagged
    if ($condition4) {
        echo "this should be flagged";
    }
}