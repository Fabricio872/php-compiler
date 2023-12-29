<?php

declare(strict_types=1);

namespace Fabricio872\PhpCompiler\Command;

use Fabricio872\PhpCompiler\Model\Config;
use Fabricio872\PhpCompiler\Service\FileService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class AbstractCommand extends Command
{
    private const CONFIG_FILE_NAME = 'php-compiler.json';

    private function getSerializer(): Serializer
    {
        $normalizers = [new ObjectNormalizer()];
        $encoders = [new JsonEncoder()];

        return new Serializer($normalizers, $encoders);
    }

    protected function getConfig(): Config
    {
        if (! file_exists(FileService::getProjectRoot() . DIRECTORY_SEPARATOR . self::CONFIG_FILE_NAME)) {
            throw new CompilerNotInitializedException();
        }

        return $this->getSerializer()->deserialize(
            file_get_contents(self::CONFIG_FILE_NAME),
            Config::class,
            'json'
        );
    }
}
