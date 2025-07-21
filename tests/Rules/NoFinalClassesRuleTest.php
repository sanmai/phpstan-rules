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
use Sanmai\PHPStanRules\Rules\NoFinalClassesRule;

/**
 * @extends SingleRuleTestCase<NoFinalClassesRule>
 */
#[CoversClass(NoFinalClassesRule::class)]

class NoFinalClassesRuleTest extends SingleRuleTestCase
{
    protected function getRule(): Rule
    {
        return new NoFinalClassesRule();
    }

    public function testRule(): void
    {
        // Test final classes in src directory - should be flagged
        $this->analyseExpectingErrorLines([__DIR__ . '/../../src/TestFixtures/regular-final-classes.php'], [
            8,  // RegularFinalClass
            16, // AnotherFinalClass
        ]);
    }

    public function testRuleAllowsNonFinalClasses(): void
    {
        $this->analyseExpectingErrorLines([__DIR__ . '/../Fixtures/NoFinalClassesRule/non-final-classes.php'], []);
    }

    public function testRuleAllowsTestFiles(): void
    {
        // All files in tests/ directory should be exempted regardless of inheritance
        $this->analyseExpectingErrorLines([__DIR__ . '/../Fixtures/NoFinalClassesRule/final-classes.php'], []);
        $this->analyseExpectingErrorLines([__DIR__ . '/../Fixtures/NoFinalClassesRule/final-classes-exceptions.php'], []);
        $this->analyseExpectingErrorLines([__DIR__ . '/../Fixtures/NoFinalClassesRule/phpunit-inheritance-test.php'], []);
        $this->analyseExpectingErrorLines([__DIR__ . '/../Fixtures/NoFinalClassesRule/edge-cases.php'], []);
        $this->analyseExpectingErrorLines([__DIR__ . '/../Fixtures/NoFinalClassesRule/direct-phpunit-test.php'], []);
    }

}
