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
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\Attributes\DataProvider;
use Sanmai\PHPStanRules\Rules\NoNestedLoopsRule;
use Sanmai\PHPStanRules\Rules\NoNestedIfStatementsRule;
use Sanmai\PHPStanRules\Rules\RequireGuardClausesRule;

/**
 * Tests that all "good" examples from README don't trigger any rules
 */
#[CoversNothing]
final class ReadmeExamplesTest extends SingleRuleTestCase
{
    private const RULES = [
        NoNestedLoopsRule::class,
        NoNestedIfStatementsRule::class,
        RequireGuardClausesRule::class,
    ];

    /**
     * @return iterable<array<Rule>>
     */
    public static function provideRules(): iterable
    {
        foreach (self::RULES as $rule) {
            yield $rule => [new $rule()];
        }
    }

    /**
     * Should have no errors - all examples are "good"
     */
    #[DataProvider('provideRules')]
    public function testRule(Rule $rule): void
    {
        $this->rule = $rule;

        $this->analyse([__DIR__ . '/../Fixtures/ReadmeExamples/good_examples.php'], []);
    }

    protected function getRule(): Rule
    {
        return $this->rule;
    }

    /**
     */
    private Rule $rule;
}
