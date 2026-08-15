<?php
use PHPUnit\Framework\TestCase;

class AppSmokeTest extends TestCase
{
    public function testConfigLoadsWithSecurityEnabled()
    {
        $config = array();
        require dirname(__DIR__) . '/application/config/config.php';

        $this->assertSame('UTF-8', $config['charset']);
        $this->assertTrue($config['csrf_protection']);
        $this->assertTrue($config['cookie_httponly']);
        $this->assertNotEmpty($config['base_url']);
    }

    public function testMigrationsAreSequentiallyNumbered()
    {
        $numbers = array();
        foreach (glob(dirname(__DIR__) . '/application/migrations/*.php') as $file) {
            $this->assertSame(1, preg_match('/^(\d{3})_/', basename($file), $m), 'Nama file migration harus NNN_nama.php: ' . basename($file));
            $numbers[] = (int) $m[1];
        }

        $this->assertNotEmpty($numbers, 'Tidak ada file migration.');
        sort($numbers);
        for ($i = 1; $i < count($numbers); $i++) {
            $this->assertGreaterThan($numbers[$i - 1], $numbers[$i], 'Nomor migration harus berurutan.');
        }
    }

    public function testApplicationPhpFilesParse()
    {
        $files = array_merge(
            glob(dirname(__DIR__) . '/application/controllers/*.php'),
            glob(dirname(__DIR__) . '/application/controllers/backend/*.php'),
            glob(dirname(__DIR__) . '/application/controllers/frontend/*.php'),
            glob(dirname(__DIR__) . '/application/controllers/api/v1/*.php'),
            glob(dirname(__DIR__) . '/application/models/*.php'),
            glob(dirname(__DIR__) . '/application/libraries/*.php'),
            glob(dirname(__DIR__) . '/application/hooks/*.php')
        );

        $this->assertNotEmpty($files);
        foreach ($files as $file) {
            try {
                token_get_all(file_get_contents($file), TOKEN_PARSE);
            } catch (ParseError $e) {
                $this->fail(basename($file) . ': ' . $e->getMessage());
            }
        }
    }
}
