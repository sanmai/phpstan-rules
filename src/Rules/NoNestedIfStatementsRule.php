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
use PhpParser\Node\Expr\Assign;
use PhpParser\Node\Stmt\Expression;
use PhpParser\Node\Stmt\If_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use Override;

use function count;

/**
 * @implements Rule<If_>
 */
final class NoNestedIfStatementsRule implements Rule
{
    public const ERROR_MESSAGE = 'Nested if statements should be avoided. Consider using guard clauses, combining conditions with &&, or extracting to a method.';

    public const IDENTIFIER = 'sanmai.noNestedIf';

    private const EXACTLY_TWO = 2;

    #[Override]
    public function getNodeType(): string
    {
        return If_::class;
    }

    /**
     * @param If_ $node
     * @return list<\PHPStan\Rules\IdentifierRuleError>
     */
    #[Override]
    public function processNode(Node $node, Scope $scope): array
    {
        // Skip if this if has elseif branches (more complex control flow)
        if ([] !== $node->elseifs) {
            return [];
        }

        // More than two statements, it is a pass
        if (count($node->stmts) > self::EXACTLY_TWO) {
            return [];
        }

        if (!self::shouldBeFlagged($node->stmts[0], $node->stmts[1] ?? null)) {
            return [];
        }

        return [
            RuleErrorBuilder::message(self::ERROR_MESSAGE)
                ->identifier(self::IDENTIFIER)
                ->line($node->getLine())
                ->build(),
        ];
    }

    private static function shouldBeFlagged(Node $first, ?Node $second): bool
    {
        // Handle case 1: exactly one statement that is an if
        if ($first instanceof If_ && null === $second) {
            return true;
        }

        if (
            !$first instanceof Expression ||
            !$first->expr instanceof Assign ||
            !$second instanceof If_
        ) {
            return false;
        }

        return true;
    }

}
