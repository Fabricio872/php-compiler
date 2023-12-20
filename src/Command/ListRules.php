<?php

namespace Fabricio872\PhpCompiler\Command;

use Fabricio872\PhpCompiler\Factories\RuleFactory;
use Fabricio872\PhpCompiler\Service\Compiler;
use Fabricio872\PhpCompiler\Service\FileService;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'list-rules',
    description: 'Lists all active rules'
)]
class ListRules extends AbstractCommand
{
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $symfonyStyle = new SymfonyStyle($input, $output);

        $i = 0;
        $symfonyStyle->table(["#", "Rule"], array_map(function ($item) use ($i) {
            return [$i++, $item];
        }, $this->getConfig()->getRules()));

        return Command::SUCCESS;
    }
}
