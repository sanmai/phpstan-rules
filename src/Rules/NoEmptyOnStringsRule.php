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
use PhpParser\Node\Expr\Empty_;
use PHPStan\Analyser\Scope;
use PHPStan\Rules\Rule;
use PHPStan\Rules\RuleErrorBuilder;

/**
 * @implements Rule<Empty_>
 */
final class NoEmptyOnStringsRule implements Rule
{
    public const ERROR_MESSAGE = 'The empty() function is not allowed on strings. Use more explicit checks like === "" or === "0" instead. Note: empty("0") ==== empty(null)';

    #[Override]
    public function getNodeType(): string
    {
        return Empty_::class;
    }

    /**
     * @param Empty_ $node
     * @return list<\PHPStan\Rules\IdentifierRuleError>
     */
    #[Override]
    public function processNode(Node $node, Scope $scope): array
    {
        $exprType = $scope->getType($node->expr);

        // Check if the type is or contains string
        if (!$this->containsStringType($exprType)) {
            return [];
        }

        return [
            RuleErrorBuilder::message(self::ERROR_MESSAGE)
                ->identifier('sanmai.noEmptyOnStrings')
                ->build(),
        ];
    }

    private function containsStringType(\PHPStan\Type\Type $type): bool
    {
        // Direct string type
        if ($type->isString()->yes()) {
            return true;
        }

        // Mixed type can contain strings
        if ($type instanceof \PHPStan\Type\MixedType) {
            return true;
        }

        // Check if it's a union type containing string
        if (!$type instanceof \PHPStan\Type\UnionType) {
            return false;
        }

        foreach ($type->getTypes() as $innerType) {
            if ($innerType->isString()->yes()) {
                return true;
            }
        }

        return false;
    }
}
