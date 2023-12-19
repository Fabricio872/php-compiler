<?php

namespace Fabricio872\PhpCompiler\Command;

use Fabricio872\PhpCompiler\Factories\RuleFactory;
use Fabricio872\PhpCompiler\Service\Compiler;
use Fabricio872\PhpCompiler\Service\FileCrawler;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'compile-file',
    description: 'Compiles provided file'
)]
class CompileFileCommand extends AbstractCommand
{
    protected function configure(): void
    {
        $this
            ->addArgument('namespace', InputArgument::REQUIRED, 'Provide namespace of file you want to compile');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $fileCrawler = new FileCrawler($this->getConfig());
        $factory = new RuleFactory();

        $compiler = new Compiler(
            $this->getConfig(),
            $fileCrawler,
            $factory
        );

        $compiler->compile($input->getArgument('namespace'));

        return Command::SUCCESS;
    }
}
