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

use Override;
use PhpParser\Node;
use PhpParser\Node\Stmt\Class_;
use PhpParser\Node\Stmt\ClassMethod;
use PHPStan\Analyser\Scope;
use PHPStan\Reflection\ReflectionProvider;
use PHPStan\Rules\IdentifierRuleError;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

use function array_slice;

/**
 * @implements Rule<Class_>
 */
final class NoStaticMethodsRule implements Rule
{
    public const ERROR_MESSAGE = 'Only one public static method is allowed per class. Static methods are impossible to mock in tests.';
    public const IDENTIFIER = 'sanmai.noStaticMethods';

    public function __construct(
        private ReflectionProvider $reflectionProvider
    ) {}

    #[Override]
    public function getNodeType(): string
    {
        return Class_::class;
    }

    /**
     * @return list<IdentifierRuleError>
     */
    #[Override]
    public function processNode(Node $node, Scope $scope): array
    {
        // Skip classes with private constructors using reflection
        if ($this->hasPrivateConstructor($node)) {
            return [];
        }

        return $this->processPublicStaticMethods($node);
    }

    /**
     * Check if class has a private constructor
     */
    private function hasPrivateConstructor(Class_ $node): bool
    {
        if (null === $node->namespacedName) {
            return false;
        }

        if (!$this->reflectionProvider->hasClass($node->namespacedName->toString())) {
            return false;
        }

        $classReflection = $this->reflectionProvider->getClass($node->namespacedName->toString());
        return $classReflection->hasConstructor() && $classReflection->getConstructor()->isPrivate();
    }

    /**
     * Process public static methods and return errors for violations
     * @return list<IdentifierRuleError>
     */
    private function processPublicStaticMethods(Class_ $node): array
    {
        $publicStaticMethods = $this->getPublicStaticMethods($node);
        $additionalMethods = array_slice($publicStaticMethods, 1);

        // Create error for each additional public static method (beyond the first one)
        $errors = [];
        foreach ($additionalMethods as $method) {
            $errors[] = RuleErrorBuilder::message(self::ERROR_MESSAGE)
                ->line($method->getLine())
                ->identifier(self::IDENTIFIER)
                ->build();
        }

        return $errors;
    }


    /**
     * @return ClassMethod[]
     */
    private function getPublicStaticMethods(Class_ $class): array
    {
        $methods = [];

        foreach ($class->stmts as $stmt) {
            if ($stmt instanceof ClassMethod && $stmt->isStatic() && $stmt->isPublic()) {
                $methods[] = $stmt;
            }
        }

        return $methods;
    }
}
