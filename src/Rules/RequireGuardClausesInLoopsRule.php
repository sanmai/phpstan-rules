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
use PhpParser\Node\Expr;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Continue_;
use PhpParser\Node\Stmt\Do_;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\For_;
use PhpParser\Node\Stmt\Foreach_;
use PhpParser\Node\Stmt\If_;
use PhpParser\Node\Stmt\Return_;
use PhpParser\Node\Stmt\While_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use Override;

use function count;
use function in_array;

/**
 * @implements Rule<Node>
 */
final class RequireGuardClausesInLoopsRule implements Rule
{
    #[Override]
    public function getNodeType(): string
    {
        return Node::class;
    }

    /**
     * @return list<\PHPStan\Rules\IdentifierRuleError>
     */
    #[Override]
    public function processNode(Node $node, Scope $scope): array
    {
        if (!$this->isLoopNode($node)) {
            return [];
        }

        $statements = $this->getLoopStatements($node);
        if (null === $statements || [] === $statements) {
            return [];
        }

        $errors = [];

        foreach ($statements as $index => $statement) {
            // Skip if it's not an if statement
            if (!$statement instanceof If_) {
                continue;
            }

            // Check if this if statement has else/elseif branches
            if (null !== $statement->else || count($statement->elseifs) > 0) {
                continue;
            }

            // Check if the if statement body contains only early returns
            if ($this->containsOnlyEarlyReturns($statement->stmts)) {
                continue;
            }

            // Check if there are statements after this if in the loop
            $hasStatementsAfter = $index < count($statements) - 1;

            // If the if body doesn't contain early returns and there are statements after it,
            // this should be a guard clause
            if ($hasStatementsAfter || count($statement->stmts) > 1) {
                $errors[] = RuleErrorBuilder::message(
                    'Use guard clauses instead of wrapping code in if statements. Consider using: if (!condition) { continue; }'
                )
                    ->identifier('sanmai.requireGuardClauses')
                    ->line($statement->getLine())
                    ->build();
            }
        }

        return $errors;
    }

    private function isLoopNode(Node $node): bool
    {
        return $node instanceof For_
            || $node instanceof Foreach_
            || $node instanceof While_
            || $node instanceof Do_;
    }

    /**
     * @return array<Stmt>|null
     */
    private function getLoopStatements(Node $node): ?array
    {
        if (!$this->isLoopNode($node)) {
            return null;
        }

        // We know it's a loop node, so we can safely access stmts property
        /** @psalm-suppress NoInterfaceProperties, MixedReturnStatement */
        /** @phpstan-ignore-next-line */
        return $node->stmts;
    }

    /**
     * @param array<Stmt> $statements
     */
    private function containsOnlyEarlyReturns(array $statements): bool
    {
        if ([] === $statements) {
            return false;
        }

        foreach ($statements as $statement) {
            // Check for continue, return, break, throw
            if ($statement instanceof Continue_ || $statement instanceof Return_) {
                continue;
            }

            if ($statement instanceof Stmt\Break_) {
                continue;
            }

            /** @var Expression $statement */
            $expr = $statement->expr;

            // Check for throw expression (PHP 8+)
            if ($expr instanceof Expr\Throw_) {
                continue;
            }

            // Check for exit/die expressions
            if ($expr instanceof Expr\Exit_) {
                continue;
            }

            // Not an early return
            return false;
        }

        return true;
    }
}
