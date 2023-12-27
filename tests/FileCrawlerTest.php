<?php

namespace Fabricio872\PhpCompilerTests;

use Fabricio872\PhpCompiler\Model\Config;
use Fabricio872\PhpCompiler\Service\FileService;
use PHPUnit\Framework\TestCase;

class FileCrawlerTest extends TestCase
{

    public function testGetIterator()
    {
        $fileCrawler = new FileService(
            (new Config())
                ->setAutoload(['Fabricio872\\PhpCompiler\\' => 'src/'])
        );

        $files = $fileCrawler->getFiles('Fabricio872\\PhpCompiler\\Model');
        $this->assertIsIterable($files);
        $this->assertContains('/app/src/Service/../../compiled/src/Model/Config.php', $files);
        $this->assertArrayHasKey('/app/src/Service/../../src/Model/Config.php', $files);
    }
}
