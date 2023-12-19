<?php

namespace Fabricio872\PhpCompiler\Service;

use Exception;
use Fabricio872\PhpCompiler\Model\Config;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RecursiveRegexIterator;
use RegexIterator;
use function Symfony\Component\String\s;

class FileCrawler
{
    public function __construct(
        private Config $config
    )
    {
    }

    /**
     * @param string $namespace
     * @return array<string, string>
     * @throws Exception
     */
    public function getFiles(string $namespace): array
    {
        $directory = new RecursiveDirectoryIterator($this->getAbsolutePath($namespace));
        $directoryIterator = new RecursiveIteratorIterator($directory);

        $fileList = [];
        foreach (new RegexIterator($directoryIterator, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH) as $file){
            $fileList[$file[0]]= $this->getTargetPath($file[0]);
        }

        return $fileList;
    }

    public function getAbsolutePath(string $namespace): string
    {
        foreach ($this->config->getAutoload() as $namespacePrefix => $dir) {
            if (s($namespace)->startsWith($namespacePrefix)) {
                $parsed = sprintf(
                    '%s/../../%s%s',
                    __DIR__,
                    $dir,
                    s($namespace)->trimStart($namespacePrefix)->replace('\\', '/')
                );
                if (is_file(sprintf('%s.php', $parsed))){
                    return sprintf('%s.php', $parsed);
                }
                return $parsed;
            }
        }
        throw new Exception(sprintf("Namespace '%s' not found in project", $namespace));
    }

    public function getTargetPath(string $source): string
    {
        return s($source)->splice(
            $this->config->getCompiledSrc(),
            strlen(sprintf('%s/../../', __DIR__)),
            0
        );
    }
}
