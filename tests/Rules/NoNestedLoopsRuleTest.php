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
use Sanmai\PHPStanRules\Rules\NoNestedLoopsRule;

/**
 * @extends SingleRuleTestCase<NoNestedLoopsRule>
 */
#[CoversClass(NoNestedLoopsRule::class)]
final class NoNestedLoopsRuleTest extends SingleRuleTestCase
{
    protected function getRule(): Rule
    {
        return new NoNestedLoopsRule();
    }

    public function test_nested_loops(): void
    {
        $this->analyseExpectingErrorLines([__DIR__ . '/../Fixtures/NoNestedLoops/nested_loops.php'], [
            12,
            19,
            27,
            38,
            48,
            83, // Foreach with nested foreach in if
            91, // Foreach with nested while in if
            99, // Foreach with nested do-while in if
        ]);
    }

    public function test_all_loop_types(): void
    {
        $this->analyseExpectingErrorLines([__DIR__ . '/../Fixtures/NoNestedLoops/all_loop_types.php'], [
            10,
            17,
            25,
            34,
            42,
            52,
            61,
            69,
            78,
            90,
            100,
            109,
            119,
        ]);
    }

    public function test_single_loop_types(): void
    {
        // This test verifies single loop types are not flagged as nested
        $this->analyseExpectingErrorLines([__DIR__ . '/../Fixtures/NoNestedLoops/single_loop_types.php'], [
            // No errors expected - these are all single loops or non-loops
        ]);
    }

    public function test_not_loops(): void
    {
        // No errors expected - all nodes are non-loops
        $this->analyse([__DIR__ . '/../Fixtures/MixedNodeTypes/not_loops.php'], []);
    }

    public function test_try_catch_nested_loops(): void
    {
        // Test that nested loops are detected even when separated by try-catch blocks
        $this->analyseExpectingErrorLines([__DIR__ . '/../Fixtures/NoNestedLoopsRule/try-catch-nested-loops.php'], [
            32, // anotherLoop() - foreach with nested foreach inside try-catch
        ]);
    }
}
