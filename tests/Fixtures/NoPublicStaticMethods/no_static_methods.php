<?php

declare(strict_types=1);

namespace TestFixtures\NoStaticMethods;

class Foo {
    public static function bar() {} // One public method is fine
    protected static function barProtected() {} // protected static is fine
    private static function privateStatic() {} // private static is fine
}

class FooBar {
    public static function bar() {} // One method is fine
    public static function baz() {} // ERROR: This is forbidden
    protected static function barProtected() {} // protected static is fine
    private static function privateStatic() {} // private static is fine
}

class FooPrivateConstructor {
    private function __construct()
    {

    }

    public static function bar() {}
    public static function baz() {} // Not error: classes with private constructors can have as many static methods
}

trait SomeConstructorTrait {
    private function __construct()
    {

    }
}

class FooPrivateConstructorTrait {
    use SomeConstructorTrait;

    public static function bar() {}
    public static function baz() {} // Not error: classes with private constructors can have as many static methods
}

trait SomeConstructorTrait2 {
    public function __construct()
    {

    }
}

class FooPublicConstructorTrait {
    use SomeConstructorTrait2;

    public static function bar() {}
    public static function baz() {}
    public static function woo() {}
}

new class () {
    public function count(): int { return 0; }

    public static function bar() {}
    public static function baz() {}
};

abstract class Foo2TestCase {
    public static function bar() {}
    public static function assertBar($bar) {} // abstract classes are fine
}

