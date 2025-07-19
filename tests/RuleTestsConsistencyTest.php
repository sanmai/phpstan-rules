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
use PHPUnit\Framework\TestCase;
use ReflectionClass;
use Sanmai\PHPStanRules\Tests\Rules\SingleRuleTestCase;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

use function basename;
use function class_exists;
use function Pipeline\take;
use function sprintf;
use function str_ends_with;
use function str_replace;
use function file_get_contents;
use function preg_match_all;
use function trim;
use function assert;

#[CoversNothing]
final class RuleTestsConsistencyTest extends TestCase
{
    /**
     * @return iterable<string, array{string}>
     */
    public static function provideRuleTestClasses(): iterable
    {
        $rulesDirectory = __DIR__ . '/Rules';

        $testFiles = take(new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($rulesDirectory, RecursiveDirectoryIterator::SKIP_DOTS)
        ))
            ->filter(static fn(SplFileInfo $file) => $file->isFile() && str_ends_with($file->getFilename(), 'Test.php'))
            ->cast(static fn(SplFileInfo $file) => $file->getPathname());

        foreach ($testFiles as $file) {
            $className = self::getClassNameFromFile($file);

            if (!class_exists($className)) {
                continue;
            }

            // Skip SingleRuleTestCase itself and non-rule tests
            if (SingleRuleTestCase::class === $className) {
                continue;
            }

            yield basename($file) => [$className];
        }
    }

    private static function getClassNameFromFile(string $filePath): string
    {
        // Convert file path to class name
        // /path/to/tests/Rules/SomeRuleTest.php -> Sanmai\PHPStanRules\Tests\Rules\SomeRuleTest
        $relativePath = str_replace(__DIR__ . '/', '', $filePath);
        $relativePath = str_replace('.php', '', $relativePath);
        $relativePath = str_replace('/', '\\', $relativePath);

        return __NAMESPACE__ . '\\' . $relativePath;
    }

    /**
     * @dataProvider provideRuleTestClasses
     */
    public function testRuleTestExtendsCorrectBaseClass(string $className): void
    {
        $reflection = new ReflectionClass($className);

        // Skip abstract classes
        if ($reflection->isAbstract()) {
            $this->addToAssertionCount(1);
            return;
        }

        $this->assertTrue(
            $reflection->isSubclassOf(SingleRuleTestCase::class),
            sprintf(
                'Rule test class %s must extend %s',
                $className,
                SingleRuleTestCase::class
            )
        );
    }

    public function testAllRuleTestsUseAnalyseExpectingErrorLines(): void
    {
        $rulesDirectory = __DIR__ . '/Rules';

        $testFiles = take(new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($rulesDirectory, RecursiveDirectoryIterator::SKIP_DOTS)
        ))
            ->filter(static fn(SplFileInfo $file) => $file->isFile() && str_ends_with($file->getFilename(), 'Test.php'))
            ->toArray();

        $checkedFiles = 0;

        foreach ($testFiles as $file) {
            assert($file instanceof SplFileInfo);
            $className = self::getClassNameFromFile($file->getPathname());

            if (!class_exists($className)) {
                continue;
            }

            // Skip SingleRuleTestCase itself
            if (SingleRuleTestCase::class === $className) {
                continue;
            }

            // Skip special test cases that test multiple rules
            if (str_ends_with($className, 'ReadmeExamplesTest')) {
                continue;
            }

            $reflection = new ReflectionClass($className);

            // Skip abstract classes
            if ($reflection->isAbstract()) {
                continue;
            }

            // Skip if not extending SingleRuleTestCase
            if (!$reflection->isSubclassOf(SingleRuleTestCase::class)) {
                continue;
            }

            $fileContents = file_get_contents($file->getPathname());
            $checkedFiles++;

            // Check each use of ->analyse() to see if it has an empty array as second parameter
            if (preg_match_all('/->analyse\([^,]+,\s*(\[[^\]]*\])\s*\)/', $fileContents, $matches)) {
                foreach ($matches[1] as $index => $errorArray) {
                    // If the error array is not empty [], then it should use analyseExpectingErrorLines
                    $trimmedArray = trim($errorArray);
                    if ('[]' !== $trimmedArray) {
                        $this->fail(sprintf(
                            'Test class %s should use analyseExpectingErrorLines() instead of analyse() when expecting errors. Found: analyse(..., %s)',
                            $className,
                            $errorArray
                        ));
                    }
                }
            }
        }

        $this->assertGreaterThan(0, $checkedFiles, 'No test files were checked');
    }
}
