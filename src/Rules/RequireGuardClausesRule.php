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
use PhpParser\Node\Expr\Yield_;
use PhpParser\Node\Expr\YieldFrom;
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Do_;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\For_;
use PhpParser\Node\Stmt\Foreach_;
use PhpParser\Node\Stmt\If_;
use PhpParser\Node\Stmt\While_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use Override;

use function count;

/**
 * @implements Rule<Stmt>
 */
final class RequireGuardClausesRule implements Rule
{
    public const ERROR_MESSAGE = 'Use guard clauses instead of wrapping code in if statements. Consider using: if (!condition) { continue; } or if (!condition) { return; }';

    public const IDENTIFIER = 'sanmai.requireGuardClauses';

    private const EXPECTED_STATEMENT_COUNT = 1;

    #[Override]
    public function getNodeType(): string
    {
        return Stmt::class;
    }

    /**
     * @return list<\PHPStan\Rules\IdentifierRuleError>
     */
    #[Override]
    public function processNode(Node $node, Scope $scope): array
    {
        $statements = $this->getLoopStatements($node);

        if (self::EXPECTED_STATEMENT_COUNT !== count($statements)) {
            return [];
        }

        if (!$statements[0] instanceof If_) {
            return [];
        }

        // Simple rule: if the loop body is ONLY an if statement, flag it
        $ifStatement = $statements[0];

        // Exception: Allow if the if body contains only return, yield, or throw
        if ($this->containsOnlyOneStatement($ifStatement->stmts)) {
            return [];
        }

        return [
            RuleErrorBuilder::message(self::ERROR_MESSAGE)
                ->identifier(self::IDENTIFIER)
                ->line($ifStatement->getLine())
                ->build(),
        ];
    }


    /**
     * @phpstan-assert-if-true For_|Foreach_|While_|Do_ $node
     * @psalm-assert-if-true For_|Foreach_|While_|Do_ $node
     */
    private function isLoopNode(Node $node): bool
    {
        if ($node instanceof For_) {
            return true;
        }

        if ($node instanceof Foreach_) {
            return true;
        }

        if ($node instanceof While_) {
            return true;
        }

        if ($node instanceof Do_) {
            return true;
        }

        return false;
    }

    private function isYieldOrYieldFrom(Node $statement): bool
    {
        // Yield statements are always wrapped in Expression nodes
        return $statement instanceof Expression && (
            $statement->expr instanceof Yield_ ||
            $statement->expr instanceof YieldFrom
        );
    }

    /**
     * @return array<Stmt>
     */
    private function getLoopStatements(Node $node): array
    {
        if (!$this->isLoopNode($node)) {
            return [];
        }

        return $node->stmts;
    }

    /**
     * @param array<Stmt> $statements
     */
    private function containsOnlyOneStatement(array $statements): bool
    {
        if ([] === $statements) {
            return false;
        }

        $count = 0;
        foreach ($statements as $statement) {
            if ($this->isYieldOrYieldFrom($statement)) {
                // Allow as many yields as needed, but with only one following statement
                $count = 0;
                continue;
            }

            $count++;

            if ($count > 1) {
                return false; // More than one statement found
            }
        }

        return true;
    }
}
