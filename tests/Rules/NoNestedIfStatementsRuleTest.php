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
final class NoNestedIfStatementsRuleTest extends SingleRuleTestCase
{
    protected function getRule(): Rule
    {
        return new NoNestedIfStatementsRule();
    }

    public function test_nested_if(): void
    {
        $this->analyseExpectingErrorLines([__DIR__ . '/../Fixtures/NoNestedIf/nested_if.php'], [
            6,
            21,
            39,
            50,
        ]);
    }

    public function test_complex_cases(): void
    {
        $this->analyseExpectingErrorLines([__DIR__ . '/../Fixtures/NoNestedIf/complex_cases.php'], [
            25,
            35, // assignment + if pattern
            70,
            82,
            92,
            93,
        ]);
    }

    public function test_edge_cases(): void
    {
        $this->analyseExpectingErrorLines([__DIR__ . '/../Fixtures/NoNestedIf/edge_cases.php'], [
            24, // If with else block should be flagged (parent has else, contains single nested if)
        ]);
    }
}
