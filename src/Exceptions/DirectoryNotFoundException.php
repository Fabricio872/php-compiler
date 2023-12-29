<?php

declare(strict_types=1);

namespace Fabricio872\PhpCompiler\Exceptions;

use Exception;

class DirectoryNotFoundException extends Exception
{
    public function __construct(string $fileName)
    {
        parent::__construct(sprintf('Directory "%s" does not exists.', $fileName));
    }
}
