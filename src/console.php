<?php

require_once __DIR__.'/../vendor/autoload.php';

use Fabricio872\PhpCompiler\Command\CompileFileCommand;
use Symfony\Component\Console\Application;

$application = new Application();

$application->add(new CompileFileCommand());

$application->run();