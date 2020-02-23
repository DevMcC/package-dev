<?php

namespace DevMcC\PackageDev\Test\Unit\Stub\Core\DependencyInjectionTest;

class DIStubNonInjectableParameter
{
    /** @var string $test */
    private $test;

    public function __construct(string $test)
    {
        $this->test = $test;
    }
}
