<?php

namespace Fabricio872\PhpCompiler\Service;

use Exception;
use Fabricio872\PhpCompiler\Factories\RuleFactoryInterface;
use Fabricio872\PhpCompiler\Model\Config;
use ReflectionClass;

class Compiler
{
    public function __construct(
        private Config               $config,
        private FileCrawler          $crawler,
        private RuleFactoryInterface $factory
    )
    {
    }

    public function compile(string $classNamespace): void
    {
        $absolutePath = $this->crawler->getAbsolutePath($classNamespace);
        $classData = file_get_contents($absolutePath);

        foreach ($this->config->getRules() as $ruleNamespace) {
            $rule = $this->factory->build($ruleNamespace);
            $classReflection = new ReflectionClass($classNamespace);
            if ($rule->isApplicable($classReflection)) {
                $classData = $rule->apply($classReflection, $classData);
            }
        }

        self::forceFilePutContents($this->crawler->getTargetPath($absolutePath), $classData);
    }

    private static function forceFilePutContents($filepath, $message): void
    {
        $isInFolder = preg_match("/^(.*)\/([^\/]+)$/", $filepath, $filepathMatches);
        if ($isInFolder) {
            $folderName = $filepathMatches[1];
            $fileName = $filepathMatches[2];
            if (!is_dir($folderName)) {
                mkdir($folderName, 0777, true);
            }
        }
        file_put_contents($filepath, $message);
    }
}
