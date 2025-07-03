<?php

/**
 * Copyright 2025 Alexey Kopytko <alexey@kopytko.com>
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 * http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */

declare(strict_types=1);

namespace Sanmai\PHPStanRules\Tests\Rules;

use PHPStan\Rules\Rule;
use PHPUnit\Framework\Attributes\CoversClass;
use Sanmai\PHPStanRules\Rules\RequireGuardClausesInLoopsRule;

/**
 * @extends SingleRuleTestCase<RequireGuardClausesInLoopsRule>
 */
#[CoversClass(RequireGuardClausesInLoopsRule::class)]
final class RequireGuardClausesInLoopsRuleTest extends SingleRuleTestCase
{
    protected function getRule(): Rule
    {
        return new RequireGuardClausesInLoopsRule();
    }

    public function test_loop_with_only_if(): void
    {
        $this->analyseExpectingErrorLines(
            [__DIR__ . '/../Fixtures/GuardClauses/loop_with_only_if.php'],
            [9, 16, 23, 31, 39, 50, 58] // All loops with only if should be flagged
        );
    }

    public function test_loop_with_if_and_more(): void
    {
        $this->analyseExpectingErrorLines(
            [__DIR__ . '/../Fixtures/GuardClauses/loop_with_if_and_more.php'],
            [] // None should be flagged - all have additional statements
        );
    }

    public function test_already_using_guard_clauses(): void
    {
        $this->analyseExpectingErrorLines(
            [__DIR__ . '/../Fixtures/GuardClauses/already_using_guard_clauses.php'],
            [] // None should be flagged - all use guard clauses correctly
        );
    }

    public function test_edge_cases(): void
    {
        $this->analyseExpectingErrorLines(
            [__DIR__ . '/../Fixtures/GuardClauses/edge_cases.php'],
            [18, 35, 45, 54, 73] // Loops with only if (various edge cases)
        );
    }

    public function test_multiple_errors(): void
    {
        $this->analyseExpectingErrorLines(
            [__DIR__ . '/../Fixtures/GuardClauses/multiple_errors.php'],
            [9, 16, 23, 25] // Multiple errors in same file
        );
    }

    public function test_yield_edge_cases(): void
    {
        $this->analyseExpectingErrorLines(
            [__DIR__ . '/../Fixtures/GuardClauses/yield_edge_cases.php'],
            [10, 19, 35, 42] // Yield and expression edge cases
        );
    }

    public function test_continue_break_edge_cases(): void
    {
        $this->analyseExpectingErrorLines(
            [__DIR__ . '/../Fixtures/GuardClauses/continue_break_edge_cases.php'],
            [9, 19, 28, 45, 52] // Mixed content and empty body cases
        );
    }
}
