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

/**
 * @extends SingleRuleTestCase<NoNestedLoopsRule>
 */
#[CoversClass(NoNestedLoopsRule::class)]
class NoNestedLoopsRuleTest extends SingleRuleTestCase
{
    protected function getRule(): Rule
    {
        return new NoNestedLoopsRule();
    }

    public function testRule(): void
    {
        $this->analyse([__DIR__ . '/../Fixtures/NoNestedLoops/nested_loops.php'], [
            [
                'Nested loops are not allowed. Use functional approaches like map(), filter(), or extract to a separate method.',
                12,
            ],
            [
                'Nested loops are not allowed. Use functional approaches like map(), filter(), or extract to a separate method.',
                19,
            ],
            [
                'Nested loops are not allowed. Use functional approaches like map(), filter(), or extract to a separate method.',
                27,
            ],
            [
                'Nested loops are not allowed. Use functional approaches like map(), filter(), or extract to a separate method.',
                38,
            ],
            [
                'Nested loops are not allowed. Use functional approaches like map(), filter(), or extract to a separate method.',
                48,
            ],
        ]);
    }
}
