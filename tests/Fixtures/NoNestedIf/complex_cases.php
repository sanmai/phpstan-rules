<?php

declare(strict_types=1);

namespace TestFixtures\NoNestedIf;

// Mock functions
function checkPermission($user)
{
    return true;
}
function isValid($data)
{
    return true;
}
function process($data) {}
function handleSpecial($data) {}
function handleError($error) {}

class ComplexCases
{
    public function testSingleStatementInNestedIf($user, $data)
    {
        // Bad - single statement in nested if
        if (checkPermission($user)) {
            if (isValid($data)) { // error: Nested if statements should be avoided
                process($data);
            }
        }
    }

    public function testMultipleStatementsInParentIf($user, $data)
    {
        // Good - multiple statements in parent if
        if (checkPermission($user)) {
            $data['user'] = $user;
            if (isValid($data)) { // No error - parent has multiple statements
                process($data);
            }
        }
    }

    public function testElseIfBranch($condition1, $condition2)
    {
        // Good - has elseif
        if ($condition1) {
            process('one');
        } elseif ($condition2) {
            if ($condition2 > 10) { // No error - parent has elseif
                process('two');
            }
        }
    }

    public function testElseBranch($condition)
    {
        // Now triggers - else no longer allowed
        if ($condition) {
            process('yes');
        } else {
            if (false === $condition) { // Still no error - parent has else (but else itself is forbidden by NoElseRule)
                process('no');
            }
        }
    }

    public function testNestedIfWithElse($data)
    {
        // Now triggers - else no longer allowed
        if (isValid($data)) {
            if ($data['special']) { // error: Nested if statements should be avoided.
                handleSpecial($data);
            } else {
                process($data);
            }
        }
    }

    public function testEmptyParentIf($data)
    {
        // Bad - empty parent if except for nested if
        if (isValid($data)) {
            if ($data['process']) { // error: Nested if statements should be avoided
                process($data);
            }
        }
    }

    public function testMultipleNestedIfs($a, $b, $c)
    {
        // Bad - multiple levels of nesting
        if ($a) {
            if ($b) { // error: Nested if statements should be avoided
                if ($c) { // error: Nested if statements should be avoided
                    process('nested');
                }
            }
        }
    }
}
