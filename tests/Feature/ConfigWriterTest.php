<?php

namespace Tests\Feature;

use ConfigWriter\ConfigWriter;
use Tests\TestCase;

class ConfigWriterTest extends TestCase
{
    private $tempDir;

    protected function setUp() : void
    {
        $this->tempDir = $this->createTemporaryDirectory();
    }

    protected function tearDown() : void
    {
        system('rm -rf ' . escapeshellarg($this->tempDir));
        $this->tempDir = null;
    }

    public function testModifyIni()
    {
        copy(__DIR__ . '/files/test.ini', $this->tempDir . '/test.ini');
        $cw = new ConfigWriter($this->tempDir . '/test.ini');
        $cw->write(
            [
                [ 'sec3', 'opt1', 'newkey' ],
                [ 'sec4', 'opt1', 'newkey' ],
                [ 'sec1', 'opt1', 'newkey' ],
                [ 'sec4', 'opt2', 'newkey' ],
                [ 'sec4', 'opt1', 'newkey2' ],
            ]
        );
        $this->assertFileEquals(
            __DIR__ . '/files/test-final.ini',
            $this->tempDir . '/test.ini'
        );
    }

    public function testCreateSSHConfig()
    {
        $cw = new ConfigWriter($this->tempDir . '/ssh.conf', [], ' ', '#');
        $cw->write(
            [
                [ null, 'PermitRootLogin', 'no' ],
                [ null, 'Port', '22' ],
            ]
        );
        $this->assertFileEquals(
            __DIR__ . '/files/ssh-final.conf',
            $this->tempDir . '/ssh.conf'
        );
    }
}
