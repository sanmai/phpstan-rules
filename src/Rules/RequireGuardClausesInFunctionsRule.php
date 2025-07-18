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
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\If_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

use function count;

/**
 * @implements Rule<Stmt>
 */
final class RequireGuardClausesInFunctionsRule implements Rule
{
    public const ERROR_MESSAGE = 'Functions/methods with void return type should use guard clauses instead of wrapping main logic in if statements. Invert the condition and return early.';

    #[Override]
    public function getNodeType(): string
    {
        return Stmt::class;
    }

    /**
     * @param Stmt $node
     * @return list<\PHPStan\Rules\IdentifierRuleError>
     */
    #[Override]
    public function processNode(Node $node, Scope $scope): array
    {
        if (!$node instanceof Function_ && !$node instanceof ClassMethod) {
            return [];
        }

        // Skip if the function has a non-void return type
        if (!$this->hasVoidOrNoReturnType($node)) {
            return [];
        }

        // Get the statements in the function body
        $statements = $node->stmts;
        if (null === $statements) {
            return [];
        }

        // Skip only if there are no statements
        if (0 === count($statements)) {
            return [];
        }

        // Check if the last statement is an if without else
        $lastStatement = $statements[count($statements) - 1];
        if (!$lastStatement instanceof If_ || null !== $lastStatement->else || count($lastStatement->elseifs) > 0) {
            return [];
        }

        // If we got here, we have a function ending with a single if that should use a guard clause
        return [
            RuleErrorBuilder::message(self::ERROR_MESSAGE)
                ->identifier('sanmai.requireGuardClausesInFunctions')
                ->line($lastStatement->getStartLine())
                ->build(),
        ];
    }

    private function hasVoidOrNoReturnType(Function_|ClassMethod $node): bool
    {
        // No return type specified
        if (null === $node->returnType) {
            return true;
        }

        // Check if it's void type
        $returnType = $node->returnType;

        // Handle identifier nodes (like 'void', 'never', etc.)
        if ($returnType instanceof Node\Identifier) {
            return 'void' === $returnType->name;
        }

        // Handle name nodes
        if ($returnType instanceof Node\Name) {
            return 'void' === $returnType->toString();
        }

        return false;
    }
}
