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
use Sanmai\PHPStanRules\Rules\NoElseRule;

/**
 * @extends SingleRuleTestCase<NoElseRule>
 */
#[CoversClass(NoElseRule::class)]
final class NoElseRuleTest extends SingleRuleTestCase
{
    protected function getRule(): Rule
    {
        return new NoElseRule();
    }

    public function test_detects_else_statements(): void
    {
        $this->analyseExpectingErrorLines(
            [__DIR__ . '/../Fixtures/NoElseRule/else_statements.php'],
            [9, 19, 22, 33, 43]
        );
    }

    public function test_allows_elseif_without_else(): void
    {
        $this->analyseExpectingErrorLines(
            [__DIR__ . '/../Fixtures/NoElseRule/elseif_without_else.php'],
            []
        );
    }
}
