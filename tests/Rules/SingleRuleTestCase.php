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

use function Pipeline\take;
use function is_array;

/**
 * @template T of Rule
 * @extends RuleTestCase<T>
 */
abstract class SingleRuleTestCase extends RuleTestCase
{
    /**
     * @param string[] $files
     * @param int[] $expectedErrorLines
     */
    public function analyseExpectingErrorLines(array $files, array $expectedErrorLines = []): void
    {
        $this->analyse($files, $this->linesToErrors($expectedErrorLines));
    }

    /**
     * @param list<array{0: string, 1: int, 2?: string|null}>|int[] $expectedErrors
     */
    public function analyse(array $files, array $expectedErrors): void
    {
        parent::analyse($files, $this->linesToErrors($expectedErrors));
    }

    /**
     * @param list<array{0: string, 1: int, 2?: string|null}>|int[] $lines
     * @return list<array{0: string, 1: int, 2?: string|null}>
     */
    private function linesToErrors(array $lines): array
    {
        /** @var string $message */
        /** @phpstan-ignore classConstant.notFound */
        $message = $this->getRule()::ERROR_MESSAGE;

        return take($lines)
            ->cast(fn(array|int $line): array => is_array($line) ? $line : [$message, $line])
            ->toList();
    }
}
