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
use Sanmai\PHPStanRules\Rules\RequireGuardClausesRule;

/**
 * @extends SingleRuleTestCase<RequireGuardClausesRule>
 */
#[CoversClass(RequireGuardClausesRule::class)]
final class RequireGuardClausesRuleTest extends SingleRuleTestCase
{
    protected function getRule(): Rule
    {
        return new RequireGuardClausesRule();
    }

    public function test_loop_with_only_if(): void
    {
        $this->analyseExpectingErrorLines(
            [__DIR__ . '/../Fixtures/RequireGuardClauses/loop_with_only_if.php'],
            [ // All loops with only if AND several statements should be flagged
                9,
                17,
                25,
                34,
                42,
            ]
        );
    }

    public function test_loop_with_if_and_more(): void
    {
        $this->analyseExpectingErrorLines(
            [__DIR__ . '/../Fixtures/RequireGuardClauses/loop_with_if_and_more.php'],
            [] // None should be flagged - all have additional statements
        );
    }

    public function test_non_loop_nodes(): void
    {
        $this->analyseExpectingErrorLines(
            [__DIR__ . '/../Fixtures/RequireGuardClauses/non_loop_nodes.php'],
            [] // No errors - these are not loops
        );
    }

    public function test_return_yield_throw_allowed(): void
    {
        $this->analyseExpectingErrorLines(
            [__DIR__ . '/../Fixtures/RequireGuardClauses/return_yield_throw_allowed.php'],
            [
                36,
                46,
                56,
                138,
                155,
                165,
            ]
        );
    }

    public function test_empty_loops(): void
    {
        $this->analyseExpectingErrorLines(
            [__DIR__ . '/../Fixtures/RequireGuardClauses/empty_loops.php'],
            [] // No errors - empty loops
        );
    }

    public function test_empty_if_body(): void
    {
        $this->analyseExpectingErrorLines(
            [__DIR__ . '/../Fixtures/RequireGuardClauses/empty_if_body.php'],
            [
                9,  // For loop with empty if body
                15, // Foreach with empty if body
                30, // Do-while with empty if body
            ]
        );
    }
}
