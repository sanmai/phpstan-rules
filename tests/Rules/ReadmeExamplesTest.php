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
use PHPUnit\Framework\Attributes\CoversNothing;
use Sanmai\PHPStanRules\Rules\NoNestedLoopsRule;
use Sanmai\PHPStanRules\Rules\NoNestedIfStatementsRule;
use Sanmai\PHPStanRules\Rules\RequireGuardClausesInLoopsRule;

/**
 * Tests that all "good" examples from README don't trigger any rules
 */
#[CoversNothing]
class ReadmeExamplesTest extends RuleTestCase
{
    public function testNoNestedLoopsRule(): void
    {
        $this->rule = new NoNestedLoopsRule();

        // Should have no errors - all examples are "good"
        $this->analyse([__DIR__ . '/../Fixtures/ReadmeExamples/good_examples.php'], []);
    }

    public function testNoNestedIfStatementsRule(): void
    {
        $this->rule = new NoNestedIfStatementsRule();

        // Should have no errors - all examples are "good"
        $this->analyse([__DIR__ . '/../Fixtures/ReadmeExamples/good_examples.php'], []);
    }

    public function testRequireGuardClausesInLoopsRule(): void
    {
        $this->rule = new RequireGuardClausesInLoopsRule();

        // Should have no errors - all examples are "good"
        $this->analyse([__DIR__ . '/../Fixtures/ReadmeExamples/good_examples.php'], []);
    }

    protected function getRule(): Rule
    {
        // This is set in each test method
        return $this->rule;
    }

    private Rule $rule;
}
