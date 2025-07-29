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

use GlobIterator;
use PhpParser\Node;
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use Sanmai\PHPStanRules\Rules\NoCountZeroComparisonRule;
use Sanmai\PHPStanRules\Rules\NoElseRule;
use Sanmai\PHPStanRules\Rules\NoEmptyOnStringsRule;
use Sanmai\PHPStanRules\Rules\NoEmptyRule;
use Sanmai\PHPStanRules\Rules\NoFinalClassesRule;
use Sanmai\PHPStanRules\Rules\NoNestedIfStatementsRule;
use Sanmai\PHPStanRules\Rules\NoNestedLoopsRule;
use Sanmai\PHPStanRules\Rules\NoStaticMethodsRule;
use Sanmai\PHPStanRules\Rules\RequireGuardClausesInFunctionsRule;
use Sanmai\PHPStanRules\Rules\RequireGuardClausesRule;
use SplFileInfo;
use ReflectionClass;
use Iterator;
use PHPStan\Rules\Rule;

use function basename;
use function file_get_contents;
use function Pipeline\take;
use function preg_match;
use function sprintf;
use function str_replace;

#[CoversNothing]
final class RulesConsistencyTest extends TestCase
{
    /** @var list<class-string> */
    private const ALL_RULES = [
        NoCountZeroComparisonRule::class,
        NoElseRule::class,
        NoEmptyOnStringsRule::class,
        NoEmptyRule::class,
        NoFinalClassesRule::class,
        NoNestedIfStatementsRule::class,
        NoNestedLoopsRule::class,
        NoStaticMethodsRule::class,
        RequireGuardClausesInFunctionsRule::class,
        RequireGuardClausesRule::class,
    ];

    public function testConstantHasAllRules(): void
    {
        /** @var Iterator<SplFileInfo> $files */
        $files = new GlobIterator(__DIR__ . '/../src/Rules/*Rule.php');

        $actualRules = take($files)
            ->cast(static fn(SplFileInfo $file) => basename($file->getFilename(), '.php'))
            ->toList();

        $expectedRules = take(self::provideRuleClasses())
            ->keys()
            ->toList();

        $this->assertEqualsCanonicalizing(
            $expectedRules,
            $actualRules,
            'ALL_RULES constant must contain all rule classes'
        );
    }

    /**
     * @return iterable<array{string, ReflectionClass<Rule<Node>>}>
     */
    public static function provideRuleClasses(): iterable
    {
        return take(self::ALL_RULES)
            ->map(static fn(string $className) => yield basename(str_replace('\\', '/', $className)) => [
                $className,
                new ReflectionClass($className),
            ]);
    }

    /** @param class-string $className */
    #[DataProvider('provideRuleClasses')]
    public function testRuleHasConstants(string $className): void
    {
        $reflection = new ReflectionClass($className);

        $this->assertTrue($reflection->hasConstant('ERROR_MESSAGE'), 'Rule class must have ERROR_MESSAGE constant');
        $constant = $reflection->getConstant('ERROR_MESSAGE');
        $this->assertIsString($constant, 'ERROR_MESSAGE must be a string');
        $this->assertNotEmpty($constant, 'ERROR_MESSAGE must not be empty');

        $this->assertTrue($reflection->hasConstant('IDENTIFIER'), 'Rule class must have IDENTIFIER constant');
        $constant = $reflection->getConstant('IDENTIFIER');
        $this->assertIsString($constant, 'IDENTIFIER must be a string');
        $this->assertNotEmpty($constant, 'IDENTIFIER must not be empty');
        $this->assertStringStartsWith('sanmai.', $constant, 'IDENTIFIER must be namespaced');
    }

    #[DataProvider('provideRuleClasses')]
    public function testRuleIsRegisteredInExtensionNeon(string $className): void
    {
        $content = file_get_contents(__DIR__ . '/../extension.neon');

        $this->assertStringContainsString(
            $className,
            $content,
            'Rule class must be registered in extension.neon',
        );
    }

    #[DataProvider('provideRuleClasses')]
    public function testRuleHasDocumentationInReadme(string $className): void
    {
        $content = file_get_contents(__DIR__ . '/../README.md');

        $ruleName = basename(str_replace('\\', '/', $className));

        $this->assertStringContainsString(
            sprintf('### `%s`', $ruleName),
            $content,
            'Rule must be documented in README.md with section header'
        );
    }

    /** @param class-string $className */
    #[DataProvider('provideRuleClasses')]
    public function testRuleUsesConstantForIdentifier(string $className): void
    {
        $reflection = new ReflectionClass($className);
        $content = file_get_contents($reflection->getFileName());

        // Look for ->identifier() calls with string literals
        if (preg_match('/->identifier\(\s*([^:)]*)\s*\)/', $content, $matches)) {
            $this->fail(sprintf('Rule must use self::IDENTIFIER constant in ->identifier() call, found "%s"', $matches[1]));
        }

        // Ensure ->identifier() is called with constants (self::IDENTIFIER pattern)
        $this->assertMatchesRegularExpression(
            '/->identifier\(\s*self::IDENTIFIER\s*\)/',
            $content,
            'Rule must use self::IDENTIFIER constant in ->identifier() call'
        );
    }
}
