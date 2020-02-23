<?php

namespace DevMcC\PackageDev\Test\Unit\Stub\Core\DependencyInjectionTest;

/**
 * Dependency Injection stub.
 *
 * Should be resolved by DI as follows:
 * - DIStubTreeAB
 * - - DIStubTreeABA
 * - - DIStubTreeABB
 * - - - DIStubTreeABBA
 */
class DIStubTreeAB
{
    /** @var DIStubTreeABA $diStubTreeABA */
    public $diStubTreeABA;
    /** @var DIStubTreeABB $diStubTreeABB */
    public $diStubTreeABB;

    public function __construct(
        DIStubTreeABA $diStubTreeABA,
        DIStubTreeABB $diStubTreeABB
    ) {
        $this->diStubTreeABA = $diStubTreeABA;
        $this->diStubTreeABB = $diStubTreeABB;
    }
}
