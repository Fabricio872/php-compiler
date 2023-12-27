<?php

declare(strict_types=1);

namespace Fabricio872\PhpCompiler\Command;

use Fabricio872\PhpCompiler\Factories\RuleFactory;
use Fabricio872\PhpCompiler\Service\Compiler;
use Fabricio872\PhpCompiler\Service\FileService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'compile',
    description: 'Compiles all namespaces'
)]
class CompileCommand extends AbstractCommand
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $symfonyStyle = new SymfonyStyle($input, $output);

        $fileCrawler = new FileService($this->getConfig());
        $factory = new RuleFactory();

        $compiler = new Compiler(
            $this->getConfig(),
            $fileCrawler,
            $factory
        );

        $paths = [];
        foreach ($this->getConfig()->getAutoload() as $namespace => $path) {
            $paths = array_merge($paths, $fileCrawler->getFiles($namespace));
        }

        $progressBar = $symfonyStyle->createProgressBar(count($paths));

        foreach ($paths as $path) {
            $progressBar->advance();
            $compiler->compile($fileCrawler->getNamespace($path));
        }
        $progressBar->finish();
        $symfonyStyle->newLine();

        return Command::SUCCESS;
    }
}
