<?php

namespace {
    abstract class TestFixtures_NoStaticMethods_Foo
    {
        public static function bar() {} // One method is fine
        public static function baz() {} // ERROR: classes with private constructors can have as many static methods
    }
}
