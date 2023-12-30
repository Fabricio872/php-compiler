<?php

declare(strict_types=1);

namespace Fabricio872\PhpCompiler\Command;

use DateTime;
use Fabricio872\PhpCompiler\Service\FileService;
use Spatie\Watcher\Watch;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'watch',
    description: 'Watches for changes in filesystem and automatically compiles changed file.'
)]
class WatchCommand extends AbstractCommand
{
    private SymfonyStyle $symfonyStyle;

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->symfonyStyle = new SymfonyStyle($input, $output);

        $this->compileAll();

        $paths = [];
        foreach ($this->getConfig()->getAutoload() as $namespace => $path) {
            $paths[] = FileService::getProjectRoot() . DIRECTORY_SEPARATOR . $path;
        }

        Watch::paths($paths)
            ->onAnyChange($this->compileAll(...))
            ->start();

        return Command::SUCCESS;
    }

    private function compileAll(): void
    {
        $this->symfonyStyle->writeln(sprintf('[%s] compiling...', (new DateTime())->format('H:i:s')));
        shell_exec(realpath(__DIR__ . '/../../compiler') . ' compile');
        $this->symfonyStyle->newLine();
    }
}
