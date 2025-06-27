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
use PhpParser\Node\Stmt\Do_;
use PhpParser\Node\Stmt\For_;
use PhpParser\Node\Stmt\Foreach_;
use PhpParser\Node\Stmt\While_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use Override;

/**
 * @implements Rule<Node>
 */
final class NoNestedLoopsRule implements Rule
{
    #[Override]
    public function getNodeType(): string
    {
        return Node::class;
    }

    /**
     * @param Node $node
     * @param Scope $scope
     * @return list<\PHPStan\Rules\IdentifierRuleError>
     */
    #[Override]
    public function processNode(Node $node, Scope $scope): array
    {
        if (!$this->isLoopNode($node)) {
            return [];
        }

        // Only check direct statements within the loop body, not inside function calls
        $stmts = $this->getLoopStatements($node);
        if (null === $stmts) {
            return [];
        }

        $hasNestedLoop = $this->hasDirectNestedLoop($stmts);

        if ($hasNestedLoop) {
            return [
                RuleErrorBuilder::message(
                    'Nested loops are not allowed. Use functional approaches like map(), filter(), or extract to a separate method.'
                )
                    ->identifier('sanmai.noNestedLoops')
                    ->build(),
            ];
        }

        return [];
    }

    private function isLoopNode(Node $node): bool
    {
        return $node instanceof For_
            || $node instanceof Foreach_
            || $node instanceof While_
            || $node instanceof Do_;
    }


    /**
     * @param Node $node
     * @return array<Node\Stmt>|null
     */
    private function getLoopStatements(Node $node): ?array
    {
        if ($node instanceof For_ || $node instanceof Foreach_ || $node instanceof While_ || $node instanceof Do_) {
            return $node->stmts;
        }

        return null;
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
