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

namespace TestFixtures\ReadmeExamples;

use function Pipeline\take;

// Mock classes and functions for the examples
class User
{
    /** @var Post[] */
    private array $posts = [];

    /** @return Post[] */
    public function getPosts(): array
    {
        return $this->posts;
    }

    public function isActive(): bool
    {
        return true;
    }

    public function hasPermission(string $permission): bool
    {
        return true;
    }
}

class Post
{
    private string $title;
    private bool $published;

    public function __construct(string $title, bool $published = true)
    {
        $this->title = $title;
        $this->published = $published;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function isPublished(): bool
    {
        return $this->published;
    }
}

class Item
{
    private bool $valid = true;
    private bool $special = false;

    public function isValid(): bool
    {
        return $this->valid;
    }

    public function isSpecial(): bool
    {
        return $this->special;
    }

    public function process(): void {}
    public function save(): void {}
}

class Record
{
    public function shouldProcess(): bool
    {
        return true;
    }
}

class GoodExamples
{
    // NoNestedLoopsRule - Good Example 1: Using sanmai/pipeline
    /**
     * @param User[] $users
     * @return string[]
     */
    public function pipelineExample(array $users): array
    {
        return take($users)
            ->map(fn($user) => yield from $user->getPosts())
            ->filter(fn($post) => $post->isPublished())
            ->cast(fn($post) => $post->getTitle())
            ->toList();
    }

    // NoNestedIfStatementsRule - Good Example 1: Combined conditions
    public function combinedConditionsExample(User $user): void
    {
        if ($user->isActive() && $user->hasPermission('edit')) {
            $this->grantAccess();
        }
    }

    // NoNestedIfStatementsRule - Good Example 2: Guard clauses
    public function guardClausesExample(User $user): void
    {
        if (!$user->isActive()) {
            return;
        }

        if (!$user->hasPermission('edit')) {
            return;
        }

        $this->grantAccess();
    }

    // RequireGuardClausesInLoopsRule - Good Example 1: Guard clause
    /**
     * @param Item[] $items
     */
    public function guardClauseInLoopExample(array $items): void
    {
        foreach ($items as $item) {
            if (!$item->isValid()) {
                continue;
            }

            $item->process();
            $item->save();
            $this->notify($item);
        }
    }

    // RequireGuardClausesInLoopsRule - Good Example 2: Early continue pattern
    public function earlyReturnPatternExample(): void
    {
        while ($record = $this->fetchNext()) {
            if (!$record->shouldProcess()) {
                continue;
            }

            $this->transform($record);
            $this->validate($record);
            $this->store($record);
        }
    }

    // Helper methods referenced in examples
    private function grantAccess(): void {}
    private function notify(Item $item): void {}
    private function fetchNext(): ?Record
    {
        static $count = 0;
        return ++$count <= 3 ? new Record() : null;
    }
    private function transform(Record $record): void {}
    private function validate(Record $record): void {}
    private function store(Record $record): void {}
}
