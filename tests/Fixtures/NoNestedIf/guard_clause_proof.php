<?php

// This file proves the guard clauses are necessary

// Case 1: Empty if - would cause undefined array offset without count check
if (true) {
}

// Case 2: If with 2+ statements where first is not an if
if (true) {
    echo "first statement";
    echo "second statement";
}

// Case 3: If with single non-if statement  
if (true) {
    echo "not an if statement";
}

// Case 4: Valid nested if that should be flagged
if (true) {
    if (false) {
        echo "nested";
    }
}