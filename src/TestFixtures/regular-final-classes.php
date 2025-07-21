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

namespace Sanmai\PHPStanRules\TestFixtures;

// This should be flagged - final class in src directory
final class RegularFinalClass
{
    public function doSomething(): void {}
}

// This should also be flagged
final class AnotherFinalClass
{
    public function doSomethingElse(): void {}
}

// This should not be flagged - not final
class RegularClass
{
    public function regularMethod(): void {}
}
