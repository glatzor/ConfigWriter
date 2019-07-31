<?php

namespace Tests;

use RuntimeException;

use PHPUnit\Framework\TestCase as BaseTestCase;

class TestCase extends BaseTestCase
{
    /**
     * Create and return the name of a temporary directory.
     *
     * @throws \RuntimeExpcetion
     *
     * @return string
     */
    protected function createTemporaryDirectory()
    {
        $prefix = (
            'configwriter-'
            . (new \ReflectionClass($this))->getShortName()
            . '-'
            . $this->getName()
        );
        $attemps    = 5;
        $path       = sprintf(
            "%s%s%s.%d",
            sys_get_temp_dir(),
            DIRECTORY_SEPARATOR,
            $prefix,
            random_int(10000000, 99999999)
        );

        do {
            if (@mkdir($path)) {
                return $path;
            }
        } while (0 < --$attemps);

        throw new RuntimeException(
            sprintf('Failed to create temporary directory [%s]', $path)
        );
    }
}
