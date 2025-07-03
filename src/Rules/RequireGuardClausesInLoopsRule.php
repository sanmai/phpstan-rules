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
use PhpParser\Node\Expr\Yield_;
use PhpParser\Node\Expr\YieldFrom;
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
    public const ERROR_MESSAGE = 'Use guard clauses instead of wrapping code in if statements. Consider using: if (!condition) { continue; }';

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

        // Simple rule: if the loop body is ONLY an if statement, it should use guard clauses
        if (1 === count($statements) && $statements[0] instanceof If_) {
            $ifStatement = $statements[0];

            // Skip if it has elseif branches or else clause
            if ([] !== $ifStatement->elseifs || null !== $ifStatement->else) {
                return $errors;
            }

            // Skip if the if body contains only early returns (already using guard pattern)
            if ($this->containsOnlyEarlyReturns($ifStatement->stmts)) {
                return $errors;
            }

            $errors[] = RuleErrorBuilder::message(self::ERROR_MESSAGE)
                ->identifier('sanmai.requireGuardClauses')
                ->line($ifStatement->getLine())
                ->build();
        }

        return $errors;
    }

    /**
     * @phpstan-assert-if-true For_|Foreach_|While_|Do_ $node
     * @psalm-assert-if-true For_|Foreach_|While_|Do_ $node
     */
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

        $hasYieldFrom = false;
        $lastStatementIndex = count($statements) - 1;

        foreach ($statements as $index => $statement) {
            // Check for continue, return, break, throw
            if ($statement instanceof Continue_ || $statement instanceof Return_) {
                continue;
            }

            if ($statement instanceof Stmt\Break_) {
                continue;
            }

            if ($statement instanceof Expression) {
                $expr = $statement->expr;

                // Check for throw expression (PHP 8+)
                if ($expr instanceof Expr\Throw_) {
                    continue;
                }

                // Check for exit/die expressions
                if ($expr instanceof Expr\Exit_) {
                    continue;
                }

                // Check for yield from expressions
                if ($expr instanceof YieldFrom) {
                    // yield from is ok if it's followed by an early return
                    if ($index < $lastStatementIndex) {
                        $hasYieldFrom = true;
                        continue;
                    }
                    // yield from as the last statement is not an early return
                    return false;
                }

                // Regular yield is not an early return
                if ($expr instanceof Yield_) {
                    return false;
                }
            }

            // Not an early return or allowed expression
            return false;
        }

        return true;
    }

}
