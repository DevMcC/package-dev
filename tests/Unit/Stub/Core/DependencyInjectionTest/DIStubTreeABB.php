<?php

namespace DevMcC\PackageDev\Test\Unit\Stub\Core\DependencyInjectionTest;

/**
 * Dependency Injection stub.
 *
 * Should be resolved by DI as follows:
 * - DIStubTreeABB
 * - - DIStubTreeABBA
 */
class DIStubTreeABB
{
    /** @var DIStubTreeABBA $diStubTreeABBA */
    public $diStubTreeABBA;

    public function __construct(
        DIStubTreeABBA $diStubTreeABBA
    ) {
        $this->diStubTreeABBA = $diStubTreeABBA;
    }
}
