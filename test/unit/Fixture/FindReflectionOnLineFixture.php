<?php

function fooFunc()
{
    // hello
}

class SomeFooClass
{
    private $someMember;

    public function someMethod()
    {
        // hello
    }
}

trait SomeFooTrait
{
}

interface SomeFooInterface
{
}

class SomeFooClassWithImplementedInterface implements \Roave\BetterReflectionTest\Fixture\AutoloadableInterface
{
}

define('FOO', 0);

const BOO = 1;
