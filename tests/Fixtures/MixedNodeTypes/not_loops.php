<?php

declare(strict_types=1);

namespace TestFixtures\MixedNodeTypes;

// Test to ensure non-loop nodes are properly ignored
class NotLoopsTest
{
    public function testIfStatement($condition)
    {
        // Regular if statement - should not trigger any loop rules
        if ($condition) {
            echo "Not a loop";
            if ($condition) {
                echo "Nested if is OK outside loops";
            }
        }
    }

    public function testSwitchStatement($value)
    {
        // Switch statement - should not trigger any loop rules
        switch ($value) {
            case 1:
                echo "One";
                break;
            case 2:
                echo "Two";
                break;
        }
    }

    public function testTryCatch()
    {
        // Try-catch - should not trigger any loop rules
        try {
            riskyOperation();
        } catch (\Exception $e) {
            handleError($e);
        }
    }

    public function testFunctionCalls()
    {
        // Function calls - should not trigger any loop rules
        array_map(function($item) {
            // This is inside a closure, not a loop
            if ($item > 0) {
                return $item * 2;
            }
            return $item;
        }, [1, 2, 3]);
    }

    public function testClassDeclaration()
    {
        // Anonymous class - should not trigger any loop rules
        $obj = new class {
            public function method()
            {
                if (true) {
                    echo "Inside anonymous class";
                }
            }
        };
    }

    public function testReturn()
    {
        // Return statement - should not trigger any loop rules
        return [
            'key' => 'value',
            'nested' => [
                'data' => 123
            ]
        ];
    }

    public function testEcho()
    {
        // Echo statement - should not trigger any loop rules
        echo "Hello";
        echo " World";
    }

    public function testAssignment()
    {
        // Assignment - should not trigger any loop rules
        $x = 1;
        $y = 2;
        $z = $x + $y;
    }
}

// Mock functions
function riskyOperation() {}
function handleError($e) {}