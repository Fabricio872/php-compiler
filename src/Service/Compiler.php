<?php

declare(strict_types=1);

namespace Fabricio872\PhpCompiler\Service;

use Fabricio872\PhpCompiler\Exceptions\FileNotFoundException;
use Fabricio872\PhpCompiler\Factories\RuleFactoryInterface;
use Fabricio872\PhpCompiler\Model\Config;
use ReflectionClass;
use ReflectionException;

class Compiler
{
    public function __construct(
        private readonly Config               $config,
        private readonly FileService          $crawler,
        private readonly RuleFactoryInterface $factory
    ) {
    }

    /**
     * @param class-string $classNamespace
     * @return void
     * @throws ReflectionException
     */
    public function compile(string $classNamespace): void
    {
        $absolutePath = $this->crawler->getAbsolutePath($classNamespace);
        if (file_exists($absolutePath)) {
            copy($absolutePath, $this->crawler->getTargetPath($absolutePath));
            $classData = file_get_contents($absolutePath);

            if (! $classData) {
                throw new FileNotFoundException($absolutePath);
            }

            foreach ($this->config->getRules() as $ruleNamespace) {
                $rule = $this->factory->build($ruleNamespace);
                $classReflection = new ReflectionClass($classNamespace);
                if ($rule->isApplicable($classReflection)) {
                    $classData = $rule->apply($classReflection, $classData);
                }
            }

            self::forceFilePutContents($this->crawler->getTargetPath($absolutePath), $classData);
        }
    }

    private static function forceFilePutContents(string $filepath, string $content): void
    {
        $isInFolder = preg_match("/^(.*)\/([^\/]+)$/", $filepath, $filepathMatches);
        if ($isInFolder) {
            $folderName = $filepathMatches[1];
            $fileName = $filepathMatches[2];
            if (! is_dir($folderName)) {
                mkdir($folderName, 0777, true);
            }
        }
        file_put_contents($filepath, $content);
    }
}
