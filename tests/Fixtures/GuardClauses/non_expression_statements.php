<?php

declare(strict_types=1);

namespace TestFixtures\GuardClauses;

// Test non-expression statements
class NonExpressionStatementsTest
{
    public function testDeclareStatement($items)
    {
        foreach ($items as $item) {
            if ($item['strict']) { // error: Use guard clauses
                declare(strict_types=1) {
                    // Some code in declare block
                    $x = 1;
                }
            }
            processItem($item);
        }
    }
    
    public function testTryCatchStatement($items)
    {
        foreach ($items as $item) {
            if ($item['risky']) { // error: Use guard clauses
                try {
                    riskyOperation();
                } catch (\Exception $e) {
                    handleError($e);
                }
            }
            processItem($item);
        }
    }
    
    public function testIfStatement($items)
    {
        foreach ($items as $item) {
            if ($item['complex']) { // error: Use guard clauses
                // Nested if is not an expression
                if ($item['subCondition']) {
                    handleSubCondition();
                }
            }
            processItem($item);
        }
    }
    
    public function testWhileStatement($items)
    {
        foreach ($items as $item) {
            if ($item['needsLoop']) { // error: Use guard clauses
                $i = 0;
                while ($i < 5) {
                    echo $i++;
                }
            }
            processItem($item);
        }
    }
    
    public function testForStatement($items)
    {
        foreach ($items as $item) {
            if ($item['needsFor']) { // error: Use guard clauses
                for ($i = 0; $i < 5; $i++) {
                    echo $i;
                }
            }
            processItem($item);
        }
    }
    
    public function testSwitchStatement($items)
    {
        foreach ($items as $item) {
            if ($item['needsSwitch']) { // error: Use guard clauses
                switch ($item['type']) {
                    case 'A':
                        handleA();
                        break;
                    case 'B':
                        handleB();
                        break;
                }
            }
            processItem($item);
        }
    }
}

// Mock functions
function processItem($item) {}
function riskyOperation() {}
function handleError($e) {}
function handleSubCondition() {}
function handleA() {}
function handleB() {}