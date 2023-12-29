<?php

declare(strict_types=1);

namespace Fabricio872\PhpCompiler\Exceptions;

use Exception;

class ShouldNotHappenException extends Exception
{
    public function __construct(string $message = 'Internal error.')
    {
        parent::__construct($message);
    }
}
