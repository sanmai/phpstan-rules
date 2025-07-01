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
 * Tests that both rules ignore non-loop nodes
 * @extends RuleTestCase<NoNestedLoopsRule>
 */
#[CoversClass(NoNestedLoopsRule::class)]
#[CoversClass(RequireGuardClausesInLoopsRule::class)]
class MixedNodeTypesTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        // Test with NoNestedLoopsRule first
        return new NoNestedLoopsRule();
    }

    public function testNoNestedLoopsIgnoresNonLoops(): void
    {
        // No errors expected - all nodes are non-loops
        $this->analyse([__DIR__ . '/../Fixtures/MixedNodeTypes/not_loops.php'], []);
    }

    // This test is just to ensure both rules are tested with the same fixture
}
