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
use Sanmai\PHPStanRules\Rules\RequireGuardClausesInFunctionsRule;

/**
 * @extends SingleRuleTestCase<RequireGuardClausesInFunctionsRule>
 */
#[CoversClass(RequireGuardClausesInFunctionsRule::class)]
final class RequireGuardClausesInFunctionsRuleTest extends SingleRuleTestCase
{
    protected function getRule(): Rule
    {
        return new RequireGuardClausesInFunctionsRule();
    }

    public function test_detects_missing_guard_clauses(): void
    {
        $this->analyseExpectingErrorLines(
            [__DIR__ . '/../Fixtures/RequireGuardClausesInFunctions/single_long_if.php'],
            [14, 25, 95]  // Lines where errors should occur based on the fixture
        );
    }

    public function test_edge_cases(): void
    {
        $this->analyseExpectingErrorLines(
            [__DIR__ . '/../Fixtures/RequireGuardClausesInFunctions/edge_cases.php'],
            [8, 27, 38, 48]  // singleStatement, onlyIfStatement, ifWithSingleStatement, and noReturnType should trigger
        );
    }
}
