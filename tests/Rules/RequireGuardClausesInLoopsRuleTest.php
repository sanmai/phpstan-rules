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
use PHPStan\Testing\RuleTestCase;
use PHPUnit\Framework\Attributes\CoversClass;
use Sanmai\PHPStanRules\Rules\NoNestedLoopsRule;
use Sanmai\PHPStanRules\Rules\RequireGuardClausesInLoopsRule;

/**
 * @extends SingleRuleTestCase<RequireGuardClausesInLoopsRule>
 */
#[CoversClass(RequireGuardClausesInLoopsRule::class)]
class RequireGuardClausesInLoopsRuleTest extends SingleRuleTestCase
{
    protected function getRule(): Rule
    {
        return new RequireGuardClausesInLoopsRule();
    }

    public function test_missing_guard_clauses_fixed(): void
    {
        // Test with the properly structured fixture
        $this->analyseExpectingErrorLines([__DIR__ . '/../Fixtures/GuardClauses/missing_guard_clauses_fixed.php'], [
            30,
            41,
            87,
        ]);
    }

    public function test_break_vs_continue(): void
    {
        $this->analyseExpectingErrorLines([__DIR__ . '/../Fixtures/GuardClauses/break_vs_continue.php'], [
            // The first function's ifs are not flagged because they contain only early returns
            // This is the correct behavior - they're already using the early return pattern

            // The second function's ifs ARE flagged:
            44,
            48,
            53,
        ]);
    }

    public function test_early_returns(): void
    {
        $this->analyseExpectingErrorLines([__DIR__ . '/../Fixtures/GuardClauses/early_returns.php'], [
            78,
            92,
            103,
            114,
        ]);
    }

    public function test_statement_order(): void
    {
        $this->analyseExpectingErrorLines([__DIR__ . '/../Fixtures/GuardClauses/statement_order.php'], [
            15,
            19,
            23,
            27,
            31,
            35,
            47,
            59,
            70,
            94,
        ]);
    }

    public function test_statement_instanceof_check(): void
    {
        $this->analyseExpectingErrorLines([__DIR__ . '/../Fixtures/GuardClauses/statement_instanceof_check.php'], [
            14,
            33,
            48,
            52,
            56,
        ]);
    }

    public function test_single_loop_types(): void
    {
        $this->analyseExpectingErrorLines([__DIR__ . '/../Fixtures/GuardClauses/single_loop_types.php'], [
            14,
            26,
            39,
            52,
        ]);
    }

    public function test_not_loops(): void
    {
        $this->analyseExpectingErrorLines([__DIR__ . '/../Fixtures/MixedNodeTypes/not_loops.php']);
    }

    public function test_non_expression_statements(): void
    {
        $this->analyseExpectingErrorLines([__DIR__ . '/../Fixtures/GuardClauses/non_expression_statements.php'], [
            13,
            26,
            40,
            53,
            66,
            78,
        ]);
    }

    public function test_multiple_ifs(): void
    {
        $this->analyseExpectingErrorLines([__DIR__ . '/../Fixtures/GuardClauses/multiple_ifs.php'], [
            14,
            18,
            22,
            43,
        ]);
    }

    public function test_multiple_early_return_ifs(): void
    {
        $this->analyseExpectingErrorLines([__DIR__ . '/../Fixtures/GuardClauses/multiple_early_return_ifs.php'], [
            26,
            32,
        ]);
    }

    public function test_mixed_statement_types(): void
    {
        $this->analyseExpectingErrorLines([__DIR__ . '/../Fixtures/GuardClauses/mixed_statement_types.php'], [
            15,
            28,
            42,
        ]);
    }

    public function test_expression_types(): void
    {
        $this->analyseExpectingErrorLines([__DIR__ . '/../Fixtures/GuardClauses/expression_types.php'], [
            14,
            30,
            48,
        ]);
    }

    public function test_edge_cases(): void
    {
        $this->analyseExpectingErrorLines([__DIR__ . '/../Fixtures/GuardClauses/edge_cases.php'], [
            46,
            50,
            80,
        ]);
    }
}
