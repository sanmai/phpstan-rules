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

namespace Sanmai\PHPStanRules\Rules;

use PhpParser\Node;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use Override;

/**
 * @implements Rule<Node\Stmt\Class_>
 */
class NoFinalClassesRule implements Rule
{
    public const ERROR_MESSAGE = 'Final classes create testing obstacles and indirection hell. Use @final annotation for static analysis protection without runtime restrictions.';

    public const IDENTIFIER = 'sanmai.noFinalClasses';

    private const PHPUNIT_TEST_CASE = \PHPUnit\Framework\TestCase::class;

    #[Override]
    public function getNodeType(): string
    {
        return Node\Stmt\Class_::class;
    }

    #[Override]
    public function processNode(Node $node, Scope $scope): array
    {
        if (!$node->isFinal()) {
            return [];
        }

        // Exception: Allow final classes that extend PHPUnit\Framework\TestCase
        if (self::extendsPhpUnitTestCase($node, $scope)) {
            return [];
        }

        return [
            RuleErrorBuilder::message(self::ERROR_MESSAGE)
                ->line($node->getLine())
                ->identifier(self::IDENTIFIER)
                ->build(),
        ];
    }

    private function extendsPhpUnitTestCase(Node\Stmt\Class_ $node, Scope $scope): bool
    {
        if (null === $node->extends) {
            return false;
        }

        // Shortcut for direct descendant
        if (self::PHPUNIT_TEST_CASE === $node->extends->toString()) {
            return false;
        }

        $classType = $scope->getClassReflection();

        if (null === $classType) {
            return false;
        }

        foreach ($classType->getParents() as $parent) {
            if (self::PHPUNIT_TEST_CASE === $parent->getName()) {
                return true;
            }
        }

        return false;
    }
}
