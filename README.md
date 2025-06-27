# sanmai/phpstan-rules

[![License](https://img.shields.io/badge/license-Apache%202.0-blue.svg)](LICENSE)
[![PHPStan](https://img.shields.io/badge/PHPStan-max%20level-brightgreen.svg)](https://phpstan.org/)

A collection of opinionated PHPStan rules focused on improving code quality by enforcing functional programming patterns and reducing complexity.

## Philosophy

These rules encourage:
- **Functional programming patterns** over imperative nested structures
- **Early returns and guard clauses** for better readability
- **Reduced cyclomatic complexity** through flatter code structures
- **Explicit code flow** that's easier to test and maintain

These principles align well with libraries like [`sanmai/pipeline`](https://github.com/sanmai/pipeline) that provide functional programming patterns as alternatives to nested loops.

## Installation

```bash
composer require --dev sanmai/phpstan-rules
```

## Configuration

Include the extension in your project's `phpstan.neon`:

```neon
includes:
    - vendor/sanmai/phpstan-rules/extension.neon
```

That's it! The rules will be automatically registered and start analyzing your code.

## Rules

### 1. NoNestedLoopsRule

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


**Note:** The rule respects function boundaries. Loops inside closures or called methods are not considered nested.

### 2. NoNestedIfStatementsRule

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

**Note:** The rule only flags simple nested ifs without else branches.

### 3. RequireGuardClausesInLoopsRule

**Enforces the use of guard clauses in loops instead of wrapping the main logic in if statements.**

This rule encourages early returns/continues to reduce nesting and improve readability.

#### Bad
```php
foreach ($items as $item) {
    if ($item->isValid()) { // Error: Use guard clauses
        $item->process();
        $item->save();
        $this->notify($item);
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
    $this->notify($item);
}
```

#### Bad - Multiple statements in if
```php
while ($record = $this->fetchNext()) {
    if ($record->shouldProcess()) { // Error: Use guard clauses
        $this->transform($record);
        $this->validate($record);
        $this->store($record);
    }
}
```

#### Good - Early continue pattern
```php
while ($record = $this->fetchNext()) {
    if (!$record->shouldProcess()) {
        continue;
    }
    
    $this->transform($record);
    $this->validate($record);
    $this->store($record);
}
```

**Note:** The rule does not flag:
- If statements that only contain early returns (`continue`, `break`, `return`)
- Single if statement at the end of a loop with only one statement inside

## Ignoring Rules

### Inline Suppression

You can suppress specific rule violations using PHPStan's ignore comments:

```php
foreach ($items as $item) {
    // @phpstan-ignore-next-line
    if ($item->isSpecial()) {
        // Complex logic that really needs to be here
    }
}
```

### Configuration File

You can disable rules globally or for specific files in your `phpstan.neon`:

```neon
parameters:
    ignoreErrors:
        - '#Nested loops are not allowed#'
        
    excludePaths:
        - tests/fixtures/*
        - legacy/*
```

### Per-File Configuration

For more granular control:

```neon
parameters:
    ignoreErrors:
        -
            message: '#Use guard clauses#'
            paths:
                - src/Legacy/*
                - src/ComplexAlgorithm.php
```

## Benefits

Using these rules helps create code that is:

1. **More testable** - Extracted methods are easier to unit test
2. **More readable** - Flatter code structure is easier to follow
3. **More maintainable** - Single responsibility methods are easier to modify
4. **Less error-prone** - Reduced complexity means fewer bugs

## Contributing

Found a bug or have a suggestion? Please open an issue on GitHub.

## License

This package is licensed under the Apache License 2.0. See [LICENSE](LICENSE) file for details.

## Credits

Created by [Alexey Kopytko](https://github.com/sanmai) and contributors.