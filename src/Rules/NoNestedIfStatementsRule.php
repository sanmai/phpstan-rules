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
use PhpParser\Node\Stmt\If_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;
use Override;

/**
 * @implements Rule<If_>
 */
final class NoNestedIfStatementsRule implements Rule
{
    public const ERROR_MESSAGE = 'Nested if statements should be avoided. Consider using guard clauses, combining conditions with &&, or extracting to a method.';

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
            return []; // this line is not covered by tests
        }

        // Look for any nested if statements
        foreach ($node->stmts as $statement) {
            if (!$statement instanceof If_) {
                continue;
            }

            // Skip if the nested if has elseif (more complex control flow)
            if ([] !== $statement->elseifs) {
                continue;
            }

            return [
                RuleErrorBuilder::message(self::ERROR_MESSAGE)
                    ->identifier('sanmai.noNestedIf')
                    ->line($statement->getLine())
                    ->build(),
            ];
        }

        return [];
    }
}
