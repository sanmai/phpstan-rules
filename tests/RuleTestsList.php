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

use IteratorAggregate;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;
use Traversable;

use function Pipeline\take;
use function str_ends_with;

/**
 * @implements IteratorAggregate<array-key, SplFileInfo>
 * @final
 */
class RuleTestsList implements IteratorAggregate
{
    public function getIterator(): Traversable
    {
        $testsDir = __DIR__ . '/Rules';
        $iterator = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($testsDir, RecursiveDirectoryIterator::SKIP_DOTS)
        );

        // @phpstan-ignore return.type
        return take($iterator)
            ->filter(static fn(SplFileInfo $file) => $file->isFile())
            ->filter(static fn(SplFileInfo $file) => str_ends_with($file->getFilename(), 'Test.php'));
    }
}
