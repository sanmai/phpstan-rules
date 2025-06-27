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
use Sanmai\PHPStanRules\Rules\NoNestedIfStatementsRule;

/**
 * @extends RuleTestCase<NoNestedIfStatementsRule>
 */
class NoNestedIfStatementsRuleTest extends RuleTestCase
{
    protected function getRule(): Rule
    {
        return new NoNestedIfStatementsRule();
    }

    public function testRule(): void
    {
        $this->analyse([__DIR__ . '/../Fixtures/NoNestedIf/nested_if.php'], [
            [
                'Nested if statements should be avoided. Consider using guard clauses, combining conditions with &&, or extracting to a method.',
                21,
            ],
            [
                'Nested if statements should be avoided. Consider using guard clauses, combining conditions with &&, or extracting to a method.',
                65,
            ],
        ]);
    }
}
