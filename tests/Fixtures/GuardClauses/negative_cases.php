<?php

/**
 * Test cases that should NOT trigger the guard clause rule
 * These test the early return statements in the rule logic
 */

// Case 1: Non-loop nodes should not trigger (tests line 60 early return)
class TestClass {
    public function method() {
        if (true) {
            echo "multiple";
            echo "statements";
            echo "in if";
        }
    }
    
    // More non-loop constructs to stress test the isLoopNode check
    public $property = "value";
    
    public function anotherMethod($param) {
        switch ($param) {
            case 1:
                return "switch is not a loop";
            default:
                break;
        }
        
        try {
            throw new Exception("try-catch is not a loop");
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }
}

// Case 2: Loops with multiple statements should NOT be flagged (tests line 69 early return)
foreach ([1, 2, 3] as $item) {
    if ($item > 1) {
        echo $item;
        print "multiple statements";
    }
    $extra = "this makes it multiple statements in loop";
}

// Case 3: For loop with multiple statements should NOT be flagged
for ($i = 0; $i < 3; $i++) {
    if ($i === 1) {
        echo $i;
        print "multiple";
    }
    $other = $i * 2; // This is the second statement in the loop
}

// Case 4: While loop with multiple statements should NOT be flagged  
$j = 0;
while ($j < 3) {
    if ($j === 1) {
        echo $j;
        print "statements";
    }
    $j++; // This makes it multiple statements
}

// Case 5: Return statements (not Expression) should not affect yield detection (tests line 121)
function withReturnStatements() {
    foreach ([1, 2, 3] as $item) {
        if ($item > 1) {
            return $item; // This is a Return_ statement, not Expression
        }
        $other = "second statement";
    }
}