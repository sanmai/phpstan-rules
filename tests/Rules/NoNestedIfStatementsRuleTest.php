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
use Sanmai\PHPStanRules\Rules\NoNestedIfStatementsRule;

/**
 * @extends SingleRuleTestCase<NoNestedIfStatementsRule>
 */
#[CoversClass(NoNestedIfStatementsRule::class)]
class NoNestedIfStatementsRuleTest extends SingleRuleTestCase
{
    protected function getRule(): Rule
    {
        return new NoNestedIfStatementsRule();
    }

    public function test_nested_if(): void
    {
        $this->analyseExpectingErrorLines([__DIR__ . '/../Fixtures/NoNestedIf/nested_if.php'], [
            7,  // Simple nested if
            15, // Nested if with multiple statements in outer if - NOW FLAGGED
            22, // If with else
            40, // Additional cases
            51, // Additional cases
        ]);
    }

    public function test_complex_cases(): void
    {
        $this->analyseExpectingErrorLines([__DIR__ . '/../Fixtures/NoNestedIf/complex_cases.php'], [
            26, // Updated line numbers due to more comprehensive rule
            37, // Additional nested if now caught
            71,
            83,
            93,
            94,
        ]);
    }

    public function xtest_multiple_statements_policy(): void
    {
        $this->analyseExpectingErrorLines(
            [__DIR__ . '/../Fixtures/NoNestedIf/multiple_statements_policy.php'],
            [
                10, // NOW flagged - multiple statements no longer exempt
                18, // Single-statement nested if
                26, // NOW flagged - multiple statements no longer exempt
            ]
        );
    }

    public function test_continue_vs_break(): void
    {
        $this->analyseExpectingErrorLines(
            [__DIR__ . '/../Fixtures/NoNestedIf/continue_vs_break.php'],
            [
                17, // The second if statement should be flagged
            ]
        );
    }

}
