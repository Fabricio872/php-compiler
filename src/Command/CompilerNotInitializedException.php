<?php

namespace Fabricio872\PhpCompiler\Command;

use Exception;

class CompilerNotInitializedException extends Exception
{
    public function __construct(string $message = "Compiler is not initialized please run \"init command\"")
    {
        parent::__construct($message);
    }
}
