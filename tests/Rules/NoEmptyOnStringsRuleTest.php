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
use Sanmai\PHPStanRules\Rules\NoEmptyOnStringsRule;

/**
 * @extends SingleRuleTestCase<NoEmptyOnStringsRule>
 */
#[CoversClass(NoEmptyOnStringsRule::class)]
final class NoEmptyOnStringsRuleTest extends SingleRuleTestCase
{
    protected function getRule(): Rule
    {
        return new NoEmptyOnStringsRule();
    }

    public function test_detects_empty_on_strings(): void
    {
        $this->analyseExpectingErrorLines(
            [__DIR__ . '/../Fixtures/NoEmptyOnStringsRule/empty_string_checks.php'],
            [7, 12, 17, 22, 27, 35, 58, 70, 75]
        );
    }

    public function test_detects_empty_on_strings_from_main_fixture(): void
    {
        $this->analyseExpectingErrorLines(
            [__DIR__ . '/../Fixtures/NoEmptyRule/empty_function_calls.php'],
            [7, 17, 22, 30, 36, 45, 60, 75, 80, 85]  // String-related and mixed type errors
        );
    }
}
