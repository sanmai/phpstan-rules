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
use PhpParser\Node\Expr\Throw_;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\ClassMethod;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\Function_;
use PhpParser\Node\Stmt\If_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

use function array_filter;

/**
 * @implements Rule<Stmt>
 */
final class RequireGuardClausesInFunctionsRule implements Rule
{
    public const ERROR_MESSAGE = 'Functions/methods with void return type should use guard clauses instead of wrapping main logic in if statements. Invert the condition and return early.';

    public const IDENTIFIER = 'sanmai.requireGuardClausesInFunctions';

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
        $statements = $node->stmts ?? [];

        // Find if statements - return early if we find more than one
        $ifStatement = null;
        $lastStatement = null;
        foreach ($statements as $statement) {
            $lastStatement = $statement;
            if (!$statement instanceof If_) {
                continue;
            }

            // If we already found one if statement, this function has multiple
            if (null !== $ifStatement) {
                return [];
            }

            $ifStatement = $statement;
        }

        // If no if statement was found, this function doesn't need guard clauses
        if (null === $ifStatement) {
            return [];
        }

        // The if statement must be the last statement and have no elseifs
        if ($ifStatement !== $lastStatement || [] !== $ifStatement->elseifs) {
            return [];
        }

        // Skip if statements with empty body (it is horrendous, but not our call)
        if ([] === array_filter($ifStatement->stmts, static fn(Node $node) => !$node instanceof Stmt\Nop)) {
            return [];
        }

        // Skip if statements that start with a throw
        if (
            $ifStatement->stmts[0] instanceof Expression &&
            $ifStatement->stmts[0]->expr instanceof Throw_
        ) {
            return [];
        }

        // If we got here, we have a function with exactly one if statement that should use a guard clause
        return [
            RuleErrorBuilder::message(self::ERROR_MESSAGE)
                ->identifier(self::IDENTIFIER)
                ->line($ifStatement->getStartLine())
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

        return false;
    }
}
