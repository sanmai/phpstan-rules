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
use PhpParser\Node\Expr\BinaryOp;
use PhpParser\Node\Expr\BinaryOp\Equal;
use PhpParser\Node\Expr\BinaryOp\Greater;
use PhpParser\Node\Expr\BinaryOp\Identical;
use PhpParser\Node\Expr\BinaryOp\NotEqual;
use PhpParser\Node\Expr\BinaryOp\NotIdentical;
use PhpParser\Node\Expr\BinaryOp\Smaller;
use PhpParser\Node\Expr\FuncCall;
use PhpParser\Node\Name;
use PhpParser\Node\Scalar\LNumber;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

use function strtolower;

/**
 * @implements Rule<BinaryOp>
 */
final class NoCountZeroComparisonRule implements Rule
{
    public const ERROR_MESSAGE = 'Avoid comparing count() with 0. Use === [] for empty arrays or !== [] for non-empty arrays instead.';

    #[Override]
    public function getNodeType(): string
    {
        return BinaryOp::class;
    }

    /**
     * @param BinaryOp $node
     * @return list<\PHPStan\Rules\IdentifierRuleError>
     */
    #[Override]
    public function processNode(Node $node, Scope $scope): array
    {
        if (!$this->isRelevantComparison($node)) {
            return [];
        }

        $left = $node->left;
        $right = $node->right;

        // Check if we have count() on either side compared with 0
        if (!($this->isCountCall($left) && $this->isZero($right)) &&
            !($this->isCountCall($right) && $this->isZero($left))) {
            return [];
        }

        return [
            RuleErrorBuilder::message(self::ERROR_MESSAGE)
                ->identifier('sanmai.noCountZeroComparison')
                ->build(),
        ];
    }

    private function isRelevantComparison(BinaryOp $node): bool
    {
        return $node instanceof Identical
            || $node instanceof Equal
            || $node instanceof NotIdentical
            || $node instanceof NotEqual
            || $node instanceof Greater
            || $node instanceof Smaller;
    }

    private function isCountCall(Node $node): bool
    {
        return $node instanceof FuncCall 
            && $node->name instanceof Name
            && 'count' === strtolower($node->name->toString());
    }

    private function isZero(Node $node): bool
    {
        return $node instanceof LNumber && 0 === $node->value;
    }
}
