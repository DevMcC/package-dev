<?php

namespace DevMcC\PackageDev\Test\Stub\Core\Autoloading;

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
