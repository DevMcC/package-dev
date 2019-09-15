<?php

namespace DevMcC\PackageDev\Environment;

use DevMcC\PackageDev\Exception\OperatingSystemNotSupported;

class OperatingSystem
{
    private const OS_NAME_WINDOWS = 'Windows NT';
    private const OS_NAME_LINUX = 'Linux';
    private const OS_NAME_MAC = 'Darwin';

    /**
     * @var string $osName
     */
    private $osName;

    public function __construct(string $osName)
    {
        $supportedOsNames = [self::OS_NAME_WINDOWS, self::OS_NAME_LINUX, self::OS_NAME_MAC];

        if (!in_array($osName, $supportedOsNames)) {
            throw new OperatingSystemNotSupported($osName);
        }

        $this->osName = $osName;
    }

    public function isWindows(): bool
    {
        return $this->osName == self::OS_NAME_WINDOWS;
    }

    public function isLinux(): bool
    {
        return $this->osName == self::OS_NAME_LINUX;
    }

    public function isMac(): bool
    {
        return $this->osName == self::OS_NAME_MAC;
    }
}
