<?php

namespace Fabricio872\PhpCompiler\Service;

use Exception;
use Fabricio872\PhpCompiler\Model\Config;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use RecursiveRegexIterator;
use RegexIterator;
use function Symfony\Component\String\s;

class FileService
{
    public function __construct(
        private Config $config
    )
    {
    }

    /**
     * @param string $namespace
     * @return array<int, string>
     * @throws Exception
     */
    public function getFiles(string $namespace): array
    {
        $directory = new RecursiveDirectoryIterator($this->getAbsolutePath($namespace));
        $directoryIterator = new RecursiveIteratorIterator($directory);

        $fileList = [];
        foreach (new RegexIterator($directoryIterator, '/^.+\.php$/i', RecursiveRegexIterator::GET_MATCH) as $file) {
            $fileList[] = $file[0];
        }

        return $fileList;
    }

    public function getNamespace(string $filename): string
    {
        foreach ($this->config->getAutoload() as $namespace => $src) {
            if (s($filename)->trimStart($this->getProjectRoot())->startsWith($src)) {
                return s($filename)
                    ->trimStart(__DIR__ . $src)
                    ->replace('/', '\\')
                    ->trimEnd('.php')
                    ->prepend($namespace);
            }
        }

        throw new Exception(sprintf("No namespace found for file: %s", $filename));
    }

    public function getAbsolutePath(string $namespace): string
    {
        foreach ($this->config->getAutoload() as $namespacePrefix => $dir) {
            if (s($namespace)->startsWith($namespacePrefix)) {
                $parsed = s($namespace)->replace($namespacePrefix, sprintf(
                        '%s/%s',
                        $this->getProjectRoot(),
                        $dir
                    )
                )->replace('\\', '/');
                if (is_file(sprintf('%s.php', $parsed))) {
                    return realpath(sprintf('%s.php', $parsed));
                }
                return realpath($parsed);
            }
        }
        throw new Exception(sprintf("Namespace '%s' not found in project", $namespace));
    }

    public function getTargetPath(string $source): string
    {
        return s($source)->splice(
            $this->config->getCompiledSrc(),
            strlen($this->getProjectRoot()),
            0
        );
    }

    public function getProjectRoot(): string
    {
        return realpath(__DIR__ . '/../../');
    }
}
