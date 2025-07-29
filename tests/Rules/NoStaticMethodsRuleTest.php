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
use Sanmai\PHPStanRules\Rules\NoStaticMethodsRule;
use ReflectionClass;
use PHPUnit\Framework\TestCase;

/**
 * @extends SingleRuleTestCase<NoStaticMethodsRule>
 */
#[CoversClass(NoStaticMethodsRule::class)]
final class NoStaticMethodsRuleTest extends SingleRuleTestCase
{
    protected function getRule(): Rule
    {
        return new NoStaticMethodsRule($this->createReflectionProvider());
    }

    public function test_detects_multiple_static_methods(): void
    {
        $this->analyseExpectingErrorLines(
            [__DIR__ . '/../Fixtures/NoStaticMethods/no_static_methods.php'],
            [
                15,
                55,
                56,
                63,
            ]
        );
    }

    public function test_detects_multiple_static_methods_in_root_namespace(): void
    {
        $this->analyseExpectingErrorLines(
            [__DIR__ . '/../Fixtures/NoStaticMethods/root_namespace.php'],
            [
                7,
            ]
        );
    }

    /**
     * This test file itself ends with Test.php and has multiple static methods
     * - it should not trigger any errors
     */
    public function test_allows_multiple_static_methods_in_test_files(): void
    {
        $this->analyse([__FILE__], []);
    }

    /**
     * PHPUnit's TestCase has multiple static methods and should not trigger errors
     */
    public function test_allows_multiple_static_methods_in_testcase_files(): void
    {
        $reflection = new ReflectionClass(TestCase::class);
        $this->analyse([$reflection->getFileName()], []);
    }

    public static function foo(): void {}

    public static function bar(): void {}
}
