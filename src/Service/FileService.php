<?php

declare(strict_types=1);

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
        private readonly Config $config
    ) {
    }

    /**
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
            if (s($filename)->after(self::getProjectRoot())->startsWith(s($src)->prepend('/'))) {
                return (string)s($filename)
                    ->trimStart(self::getProjectRoot() . $src)
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
                $parsed = s($namespace)->replace(
                    $namespacePrefix,
                    sprintf(
                        '%s/%s',
                        self::getProjectRoot(),
                        $dir
                    )
                )->replace('\\', '/');
                if (is_file(sprintf('%s.php', $parsed))) {
                    $parsed = sprintf('%s.php', $parsed);
                    return realpath($parsed) ?: throw new Exception(sprintf("File \"%s\" does not exist", $parsed));
                }
                return realpath((string)$parsed) ?: throw new Exception(sprintf("Directory \"%s\" does not exist", $parsed));
            }
        }
        throw new Exception(sprintf("Namespace '%s' not found in project", $namespace));
    }

    public function getTargetPath(string $source): string
    {
        return s($source)->splice(
            $this->config->getCompiledSrc(),
            strlen(self::getProjectRoot()),
            0
        );
    }

    public static function getProjectRoot(): string
    {
        return getcwd();
    }
}
