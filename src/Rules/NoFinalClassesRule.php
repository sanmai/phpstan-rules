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
use PHPStan\Reflection\ReflectionProvider;
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

    public function __construct(
        private ReflectionProvider $reflectionProvider
    ) {}

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
        if ($this->extendsPhpUnitTestCase($node, $scope)) {
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

        // Direct inheritance check
        if (self::PHPUNIT_TEST_CASE === $node->extends->toString()) {
            return true;
        }

        $classType = $scope->getClassReflection();

        // Fallback: If scope doesn't have class reflection, use ReflectionProvider
        if (null === $classType && null !== $node->namespacedName) {
            $fullClassName = $node->namespacedName->toString();
            if ($this->reflectionProvider->hasClass($fullClassName)) {
                $classType = $this->reflectionProvider->getClass($fullClassName);
            }
        }

        if (null === $classType) {
            return false;
        }

        // Use built-in inheritance check
        if (!$this->reflectionProvider->hasClass(self::PHPUNIT_TEST_CASE)) {
            return false;
        }

        return $classType->isSubclassOf(self::PHPUNIT_TEST_CASE);
    }

}
