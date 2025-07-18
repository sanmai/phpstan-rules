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
use PhpParser\Node\Stmt;
use PhpParser\Node\Stmt\Do_;
use PhpParser\Node\Stmt\For_;
use PhpParser\Node\Stmt\Foreach_;
use PhpParser\Node\Stmt\While_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use Override;

/**
 * @implements Rule<Stmt>
 */
final class NoNestedLoopsRule implements Rule
{
    public const ERROR_MESSAGE = 'Nested loops are not allowed. They hide O(n²) complexity and make code harder to test. Use lookup tables/arrays to reduce complexity to O(n), or functional approaches (map/filter/reduce) that avoid nested iteration.';

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
        // Only check direct statements within the loop body, not inside function calls
        $stmts = $this->getLoopStatements($node);
        if (null === $stmts) {
            return [];
        }

        $hasNestedLoop = $this->hasDirectNestedLoop($stmts);

        if (!$hasNestedLoop) {
            return [];
        }

        return [
            RuleErrorBuilder::message(self::ERROR_MESSAGE)
                ->identifier('sanmai.noNestedLoops')
                ->build(),
        ];
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
     * @return array<Node\Stmt>|null
     */
    private function getLoopStatements(Node $node): ?array
    {
        if (!$this->isLoopNode($node)) {
            return null;
        }

        return $node->stmts;
    }

    /**
     * @param array<Node> $stmts
     */
    private function hasDirectNestedLoop(array $stmts): bool
    {
        // Simply check if any direct statement is a loop
        foreach ($stmts as $stmt) {
            if ($this->isLoopNode($stmt)) {
                return true;
            }
        }

        return false;
    }
}
