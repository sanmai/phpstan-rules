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
use PHPStan\Type\TypeCombinator;
use PHPStan\Type\UnionType;

/**
 * @implements Rule<Empty_>
 */
final class NoEmptyRule implements Rule
{
    public const ERROR_MESSAGE = 'The empty() function is not allowed. Use more explicit checks like === null, === [] instead.';

    public const IDENTIFIER = 'sanmai.noEmpty';

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

        // Allow empty() on nullable arrays only
        if ($this->isNullableArray($exprType)) {
            return [];
        }

        // Skip string types - they're handled by NoEmptyOnStringsRule
        if ($this->containsStringType($exprType)) {
            return [];
        }

        return [
            RuleErrorBuilder::message(self::ERROR_MESSAGE)
                ->identifier(self::IDENTIFIER)
                ->build(),
        ];
    }

    private function isNullableArray(\PHPStan\Type\Type $type): bool
    {
        // For nullable arrays, check if removing null leaves just array
        $typeWithoutNull = TypeCombinator::removeNull($type);

        // If the type changed when removing null AND what's left is an array,
        // then original was a nullable array
        return !$type->equals($typeWithoutNull) && $typeWithoutNull->isArray()->yes();
    }

    private function containsStringType(\PHPStan\Type\Type $type): bool
    {
        // Direct string type
        if ($type->isString()->yes()) {
            return true;
        }

        // Check if it's a union type containing string
        if (!$type instanceof UnionType) {
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
