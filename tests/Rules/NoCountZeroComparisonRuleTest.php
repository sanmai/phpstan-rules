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
use Sanmai\PHPStanRules\Rules\NoCountZeroComparisonRule;

/**
 * @extends SingleRuleTestCase<NoCountZeroComparisonRule>
 */
#[CoversClass(NoCountZeroComparisonRule::class)]
final class NoCountZeroComparisonRuleTest extends SingleRuleTestCase
{
    protected function getRule(): Rule
    {
        return new NoCountZeroComparisonRule();
    }

    public function test_detects_count_zero_comparisons(): void
    {
        $this->analyseExpectingErrorLines(
            [__DIR__ . '/../Fixtures/NoCountZeroComparison/count_zero_comparisons.php'],
            [7, 12, 17, 22, 27, 32, 37, 42, 47, 52, 57, 61]
        );
    }

    public function test_allows_other_count_comparisons(): void
    {
        $this->analyseExpectingErrorLines(
            [__DIR__ . '/../Fixtures/NoCountZeroComparison/allowed_comparisons.php'],
            [53] // COUNT (uppercase) should also be detected
        );
    }

    public function test_non_comparison_operations(): void
    {
        $this->analyseExpectingErrorLines(
            [__DIR__ . '/../Fixtures/NoCountZeroComparison/non_comparison_operations.php'],
            []
        );
    }

    public function test_non_function_calls(): void
    {
        $this->analyseExpectingErrorLines(
            [__DIR__ . '/../Fixtures/NoCountZeroComparison/non_function_calls.php'],
            [30] // Only the actual count() call should be flagged
        );
    }
}
