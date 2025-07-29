<?php

declare(strict_types=1);

namespace TestFixtures\NoStaticMethods;

class Foo {
    public static function bar() {} // One public method is fine
    protected static function barProtected() {} // protected static is fine
    private static function privateStatic() {} // protected static is fine
}

class FooBar {
    public static function bar() {} // One method is fine
    public static function baz() {} // ERROR: This is forbidden
    protected static function barProtected() {} // protected static is fine
    private static function privateStatic() {} // protected static is fine
}

class FooPrivateConstructor {
    private function __construct()
    {

    }

    public static function bar() {} // One method is fine
    public static function baz() {} // Not error: classes with private constructors can have as many static methods
}

trait PrivateConstructorTrait {
    private function __construct()
    {

    }
}

class FooPrivateConstructorTrait {
    use PrivateConstructorTrait;

    public static function bar() {} // One method is fine
    public static function baz() {} // Not error: classes with private constructors can have as many static methods
}
