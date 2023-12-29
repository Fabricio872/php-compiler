<?php

declare(strict_types=1);

namespace Fabricio872\PhpCompiler\Exceptions;

use Exception;

class ClassNotFoundException extends Exception
{
    public function __construct(string $className)
    {
        parent::__construct(sprintf('Class "%s" not found.', $className));
    }
}
