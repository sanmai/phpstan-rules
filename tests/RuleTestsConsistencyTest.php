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

namespace Sanmai\PHPStanRules\Tests;

use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use SplFileInfo;

use function file_get_contents;
use function Pipeline\take;
use function sprintf;

#[CoversNothing]
final class RuleTestsConsistencyTest extends TestCase
{
    /**
     * @return iterable<array{string}>
     */
    public static function provideRuleTestClasses(): iterable
    {
        return take(new RuleTestsList())
            ->cast(static fn(SplFileInfo $file) => [$file->getRealPath()]);
    }

    #[DataProvider('provideRuleTestClasses')]
    public function testRuleTestExtendsCorrectBaseClass(string $filePath): void
    {
        $content = file_get_contents($filePath);

        $this->assertMatchesRegularExpression(
            '/class\s+\w+\s+extends\s+SingleRuleTestCase/',
            $content,
            sprintf('Test %s must extend SingleRuleTestCase', $filePath)
        );
    }
}
