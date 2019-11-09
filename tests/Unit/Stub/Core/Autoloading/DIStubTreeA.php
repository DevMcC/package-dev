<?php

namespace DevMcC\PackageDev\Test\Stub\Core\Autoloading;

/**
 * Dependency Injection stub.
 *
 * Should be resolved by DI as follows:
 * - DIStubTreeA
 * - - DIStubTreeAA
 * - - - DIStubTreeAAA
 * - - - DIStubTreeAAB
 * - - DIStubTreeAB
 * - - - DIStubTreeABA
 * - - - DIStubTreeABB
 * - - - - DIStubTreeABBA
 */
class DIStubTreeA
{
    /** @var DIStubTreeAA $diStubTreeAA */
    public $diStubTreeAA;
    /** @var DIStubTreeAB $diStubTreeAB */
    public $diStubTreeAB;

    public function __construct(
        DIStubTreeAA $diStubTreeAA,
        DIStubTreeAB $diStubTreeAB
    ) {
        $this->diStubTreeAA = $diStubTreeAA;
        $this->diStubTreeAB = $diStubTreeAB;
    }
}
