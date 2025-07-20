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

use Later\Interfaces\Deferred;
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\TestCase;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

use function Later\lazy;
use function Pipeline\take;
use function sprintf;
use function str_contains;
use function str_replace;
use function preg_match;

#[CoversNothing]
final class UnusedFixturesTest extends TestCase
{
    /**
     * @var Deferred<array<string, string>>
     */
    private static Deferred $usedFixtures;

    public static function setUpBeforeClass(): void
    {
        self::$usedFixtures = lazy(self::extractFixtureNamesFromTests());
    }

    /**
     * @return iterable<array<string, string>>
     */
    public static function extractFixtureNamesFromTests(): iterable
    {
        $result = take(new RuleTestsList())
            ->map(fn(SplFileInfo $file) => yield from $file->openFile('r'))
            ->filter(fn(string $line) => str_contains($line, 'Fixtures'))
            ->cast(fn(string $line) => preg_match("#Fixtures/(.+)'#", $line, $matches) ? $matches[1] : null)
            ->filter(strict: true)
            ->map(fn(string $fixture) => yield $fixture => $fixture)
            ->toAssoc()
        ;
        /** @var array<string, string> $result */

        yield $result;
    }

    /**
     * @return iterable<array{0: string}>
     */
    public static function provideFixtures(): iterable
    {
        $fixturesDir = __DIR__ . '/Fixtures';
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($fixturesDir, RecursiveDirectoryIterator::SKIP_DOTS)
        );

        return take($iterator)
            ->filter(fn(SplFileInfo $file) => 'php' === $file->getExtension())  /** @phpstan-ignore argument.type */
            ->cast(fn(SplFileInfo $file) => [str_replace($fixturesDir . '/', '', $file->getRealPath())]);  /** @phpstan-ignore argument.type */
    }

    #[DataProvider('provideFixtures')]
    public function test_fixtures_used(string $filename): void
    {
        $this->assertArrayHasKey(
            $filename,
            self::$usedFixtures->get(),
            sprintf(
                'Fixture "%s" is not used in any test. Please remove it.',
                $filename
            )
        );
    }
}
