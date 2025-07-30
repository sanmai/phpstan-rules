<?php

namespace {
    final class TestFixtures_NoStaticMethods_Foo
    {
        public static function bar() {} // One method is fine
        public static function baz() {} // ERROR: Second public static method not allowed
    }
}
