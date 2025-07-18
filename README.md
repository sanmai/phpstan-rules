# sanmai/phpstan-rules

[![License](https://img.shields.io/badge/license-Apache%202.0-blue.svg)](LICENSE)
[![PHPStan](https://img.shields.io/badge/PHPStan-max%20level-brightgreen.svg)](https://phpstan.org/)

A collection of opinionated PHPStan rules focused on enforcing functional programming patterns and reducing complexity. These rules are tailored specifically to the kind of code LLMs are prone to produce.

## Philosophy

These rules encourage:
- Functional programming patterns over imperative nested structures
- Early returns and guard clauses for better readability
- Reduced cyclomatic complexity through flatter code structures
- Explicit code flow that's easier to test and maintain

These principles align well with libraries like [`sanmai/pipeline`](https://github.com/sanmai/pipeline) that provide functional programming patterns as alternatives to nested loops.

## Installation

```bash
composer require --dev sanmai/phpstan-rules
```

If you also install `phpstan/extension-installer` then you're all set!

```bash
composer require --dev phpstan/extension-installer
```

### Manual Installation

If you don't want to use `phpstan/extension-installer`, include the extension in your project's `phpstan.neon`:

```neon
includes:
    - vendor/sanmai/phpstan-rules/extension.neon
```

## Rules

### `NoNestedLoopsRule`

**Prevents nested loops within the same function scope.**

This rule encourages extracting nested loops into separate methods or using functional approaches like `array_map()`, `array_filter()`, or the `sanmai/pipeline` library.

#### Bad
```php
foreach ($users as $user) {
    foreach ($user->getPosts() as $post) { // Error: Nested loops are not allowed
        if ($post->isPublished()) {
            $titles[] = $post->getTitle();
        }
    }
}
```

#### Good - Using sanmai/pipeline
```php
use function Pipeline\take;

$titles = take($users)
    ->map(fn($user) => yield from $user->getPosts())
    ->filter(fn($post) => $post->isPublished())
    ->cast(fn($post) => $post->getTitle())
    ->toList();
```

### `NoNestedIfStatementsRule`

**Discourages simple nested if statements without else branches.**

This rule promotes combining conditions with logical operators or using guard clauses for flatter code structure.

#### Bad
```php
if ($user->isActive()) {
    if ($user->hasPermission('edit')) { // Error: Nested if statements should be avoided
        $this->grantAccess();
    }
}
```

#### Good - Combined conditions
```php
if ($user->isActive() && $user->hasPermission('edit')) {
    $this->grantAccess();
}
```

#### Good - Guard clauses
```php
if (!$user->isActive()) {
    return;
}

if (!$user->hasPermission('edit')) {
    return;
}

$this->grantAccess();
```

### `RequireGuardClausesInLoopsRule`

**Enforces the use of guard clauses in loops instead of wrapping the main logic in if statements.**

This rule encourages early returns/continues to reduce nesting and improve readability.

**Exception**: Loops where the if statement contains only `return`, `yield`, `yield from`, or `throw` statements are allowed, as these are common patterns for filtering/searching operations.

#### Bad - Loop with only if
```php
foreach ($items as $item) {
    if ($item->isValid()) { // Error: Use guard clauses
        $item->process();
        $item->save();
    }
}
```

#### Good - Guard clause
```php
foreach ($items as $item) {
    if (!$item->isValid()) {
        continue;
    }
    
    $item->process();
    $item->save();
}
```

#### Good - If with other statements (allowed)
```php
foreach ($items as $item) {
    if (count($buffer) >= $limit) { // OK: Loop has more than just the if
        array_shift($buffer);
    }
    
    $buffer[] = $item;
}
```

### `NoElseRule`

**Forbids the use of `else` statements.**

This rule enforces the use of early returns and guard clauses instead of `else` branches, leading to flatter and more readable code.

#### Bad
```php
if ($user->isActive()) {
    return $user->getName();
} else { // Error: Else statements are not allowed
    return 'Guest';
}
```

#### Good
```php
if (!$user->isActive()) {
    return 'Guest';
}

return $user->getName();
```

### `NoEmptyRule`

**Forbids the use of the `empty()` function (except on nullable arrays).**

This rule encourages more explicit checks instead of the ambiguous `empty()` function, which can hide bugs and make code harder to understand. The only exception is nullable arrays (`?array`) where `empty()` provides a cleaner syntax than `$items === null || $items === []`.

#### Bad
```php
if (empty($data)) { // Error: The empty() function is not allowed
    return null;
}

if (empty($items)) { // Error: Use $items === [] instead
    return 'No items';
}
```

#### Good
```php
// Be explicit about what you're checking
if ($data === null) {
    return null;
}

// For arrays
if ($items === []) {
    return 'No items';
}

// Exception: nullable arrays are allowed
function process(?array $items): void
{
    if (empty($items)) { // OK: cleaner than ($items === null || $items === [])
        return;
    }
    // process items...
}
```

### `NoEmptyOnStringsRule`

**Forbids the use of `empty()` on string types with a specific warning about the `'0'` gotcha.**

This rule specifically targets string types and mixed types (which could contain strings) to warn about the dangerous behavior where `empty('0')` returns `true`. This has caused real bugs in production systems where users couldn't search for "0" or use "0" as a valid input.

#### Bad
```php
function validateTag(string $tag): void
{
    if (empty($tag)) { // Error: empty() on strings - empty('0') returns true!
        throw new \InvalidArgumentException('Tag cannot be empty');
    }
}

// This will throw an exception even though '0' is a valid tag!
validateTag('0');
```

#### Good
```php
function validateTag(string $tag): void
{
    if ($tag === '') { // Explicit check that doesn't treat '0' as empty
        throw new \InvalidArgumentException('Tag cannot be empty');
    }
}

// For nullable strings, be explicit about both conditions
function process(?string $input): void
{
    if ($input === null || $input === '') {
        return;
    }
    // process input...
}
```

### `RequireGuardClausesInFunctionsRule`

**Requires guard clauses in functions/methods that end with a single large if statement.**

This rule detects functions with void return types (or no return type) that end with a single `if` statement containing the main logic. These should be refactored to use guard clauses with early returns, reducing nesting and improving readability.

#### Bad
```php
function processData(): void
{
    $this->initialize();

    if ($this->isValid()) { // Error: Use guard clause instead
        $this->transform();
        $this->validate();
        $this->save();
        $this->notify();
    }
}
```

#### Good
```php
function processData(): void
{
    $this->initialize();

    if (!$this->isValid()) {
        return;
    }

    $this->transform();
    $this->validate();
    $this->save();
    $this->notify();
}
```

This rule only applies to functions with `void` return type or no return type. Functions that declare specific return types are exempt, for now.

### `NoCountZeroComparisonRule`

**Forbids comparing `count()` with 0.**

This rule encourages using direct array comparisons (`=== []` or `!== []`) instead of counting elements, which is more efficient and clearer.

#### Bad
```php
if (count($items) === 0) { // Error: Avoid comparing count() with 0
    return 'No items';
}

if (count($items) > 0) { // Error: Avoid comparing count() with 0
    process($items);
}
```

#### Good
```php
if ($items === []) {
    return 'No items';
}

if ($items !== []) {
    process($items);
}

// Other count comparisons are fine
if (count($items) === 1) {
    return 'Single item';
}
```

## Ignoring Rules

[Please refer to the PHPStan documentation.](https://phpstan.org/user-guide/ignoring-errors)

## Contributing

Found a bug or have a suggestion? [Please open an issue.](https://github.com/sanmai/phpstan-rules/issues)
