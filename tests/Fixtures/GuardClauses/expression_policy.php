<?php

/**
 * Test the policy constraint: isYieldOrYieldFrom should only process Expression nodes
 */

function testYieldExpressions() {
    foreach ([1, 2, 3] as $item) {
        if ($item > 1) {
            yield $item; // This is Expression(Yield_)
            echo "after yield"; // This should reset count due to yield
        }
    }
}

function testReturnStatements() {
    foreach ([1, 2, 3] as $item) {
        if ($item > 1) {
            return $item; // This is Return_, not Expression - should NOT reset count
            echo "unreachable"; // This makes it multiple statements
        }
    }
}

function testThrowStatements() {
    foreach ([1, 2, 3] as $item) {
        if ($item > 1) {
            throw new Exception("error"); // This is Throw_, not Expression
            echo "unreachable"; // Multiple statements
        }
    }
}

function testMixedStatements() {
    foreach ([1, 2, 3] as $item) {
        if ($item > 1) {
            $var = yield $item; // Expression(Yield_) - should reset count
            echo "after"; // Should be counted as 1 statement after reset
        }
    }
}