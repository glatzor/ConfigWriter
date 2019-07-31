<?php
namespace Tests\Linting;

use PHPUnit\Framework\TestCase;

final class LintingTest extends TestCase
{
    /**
     * @slowThreshold 15000
     *
     * @throws \Exception
     */
    public function testConfirmPHPSyntax()
    {
        passthru('php -l ' . __DIR__ . '/../../src/ConfigWriter.php', $retval);
        $this->assertEquals($retval, 0);
    }

    /**
     * @throws \Exception
     */
    public function testConfirmPHPCodingStyle()
    {
        passthru('vendor/bin/phpcs ' . __DIR__ . '/../../src', $retval);
        $this->assertEquals($retval, 0);
        passthru('vendor/bin/phpcs ' . __DIR__ . '/../../tests', $retval);
        $this->assertEquals($retval, 0);
    }
}
