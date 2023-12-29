<?php

declare(strict_types=1);

namespace Fabricio872\PhpCompiler\Command;

use DateTime;
use Exception;
use Fabricio872\PhpCompiler\Factories\RuleFactory;
use Fabricio872\PhpCompiler\Service\Compiler;
use Fabricio872\PhpCompiler\Service\FileService;
use Spatie\Watcher\Watch;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'watch',
    description: 'Watches for changes in filesystem and automatically compiles changed file.'
)]
class WatchCommand extends AbstractCommand
{
    private readonly FileService $fileService;

    private SymfonyStyle $symfonyStyle;

    public function __construct(string $name = null)
    {
        parent::__construct($name);
        $this->fileService = new FileService($this->getConfig());
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->symfonyStyle = new SymfonyStyle($input, $output);

        $this->compileAll();

        $paths = [];
        foreach ($this->getConfig()->getAutoload() as $namespace => $path) {
            $paths[] = FileService::getProjectRoot() . DIRECTORY_SEPARATOR . $path;
        }

        Watch::paths($paths)
            ->onFileCreated($this->compile(...))
            ->onFileUpdated($this->compile(...))
            ->onFileDeleted($this->compileAll(...))
            ->start();

        return Command::SUCCESS;
    }

    private function compileAll(): void
    {
        $paths = [];
        foreach ($this->getConfig()->getAutoload() as $namespace => $path) {
            $paths = array_merge($paths, $this->fileService->getFiles($namespace));
        }

        ProgressBar::setFormatDefinition('custom', "[%message%] %current%/%max% [%bar%] %percent%%");
        $progressBar = $this->symfonyStyle->createProgressBar(count($paths));
        $progressBar->setFormat('custom');

        foreach ($paths as $path) {
            $this->compile($path, false);
            $progressBar->setMessage((new DateTime())->format('H:i:s'));
            $progressBar->advance();
        }
        $progressBar->finish();
        $this->symfonyStyle->newLine();
    }

    private function compile(string $path, bool $message = true): string
    {
        $factory = new RuleFactory();

        $compiler = new Compiler(
            $this->getConfig(),
            $this->fileService,
            $factory
        );

        if ($message) {
            $this->symfonyStyle->writeln(sprintf('[%s] Compiling file "%s"', (new DateTime())->format('H:i:s'), $path));
        }

        try {
            $compiler->compile($this->fileService->getNamespace($path));
        } catch (Exception $exception) {
            $this->symfonyStyle->title(sprintf("In %s line %s:", $exception->getFile(), $exception->getLine()));
            $this->symfonyStyle->error($exception->getMessage());
        }

        return $path;
    }
}
