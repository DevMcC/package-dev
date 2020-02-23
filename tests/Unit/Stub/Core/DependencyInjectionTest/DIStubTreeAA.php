<?php

namespace DevMcC\PackageDev\Test\Unit\Stub\Core\DependencyInjectionTest;

/**
 * Dependency Injection stub.
 *
 * Should be resolved by DI as follows:
 * - DIStubTreeAA
 * - - DIStubTreeAAA
 * - - DIStubTreeAAB
 */
class DIStubTreeAA
{
    /** @var DIStubTreeAAA $diStubTreeAAA */
    public $diStubTreeAAA;
    /** @var DIStubTreeAAB $diStubTreeAAB */
    public $diStubTreeAAB;

    public function __construct(
        DIStubTreeAAA $diStubTreeAAA,
        DIStubTreeAAB $diStubTreeAAB
    ) {
        $this->diStubTreeAAA = $diStubTreeAAA;
        $this->diStubTreeAAB = $diStubTreeAAB;
    }
}
