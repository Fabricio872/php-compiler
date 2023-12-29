<?php

declare(strict_types=1);

namespace Fabricio872\PhpCompiler\Exceptions;

use Exception;

class NoNamespaceFoundException extends Exception
{
    public function __construct(string $filename)
    {
        parent::__construct(sprintf("No namespace found for file: %s", $filename));
    }
}
