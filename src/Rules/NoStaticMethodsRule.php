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
use PhpParser\Node\Stmt\TraitUse;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

use function array_slice;
use function count;

/**
 * @implements Rule<Class_>
 */
final class NoStaticMethodsRule implements Rule
{
    public const ERROR_MESSAGE = 'Only one public static method is allowed per class. Static methods are impossible to mock in tests.';

    #[Override]
    public function getNodeType(): string
    {
        return Class_::class;
    }

    #[Override]
    public function processNode(Node $node, Scope $scope): array
    {

        // Skip if class has private constructor (factory pattern exception)
        if ($this->hasPrivateConstructor($node)) {
            return [];
        }

        $publicStaticMethods = $this->getPublicStaticMethods($node);

        if (count($publicStaticMethods) <= 1) {
            return [];
        }

        $errors = [];
        foreach (array_slice($publicStaticMethods, 1) as $method) {
            $errors[] = RuleErrorBuilder::message(self::ERROR_MESSAGE)
                ->line($method->getLine())
                ->build();
        }

        return $errors;
    }

    private function hasPrivateConstructor(Class_ $class): bool
    {
        // Check direct constructor
        foreach ($class->stmts as $stmt) {
            if ($stmt instanceof ClassMethod && '__construct' === $stmt->name->toString()) {
                return $stmt->isPrivate();
            }
        }

        // Check traits for private constructor
        foreach ($class->stmts as $stmt) {
            if ($stmt instanceof TraitUse) {
                // We assume if a trait is used, it might have a private constructor
                // In practice, this would need more sophisticated analysis
                return true;
            }
        }

        return false;
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
