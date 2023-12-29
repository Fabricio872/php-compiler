<?php

declare(strict_types=1);

namespace Fabricio872\PhpCompiler\Exceptions;

use Exception;
use Fabricio872\PhpCompiler\Rules\RuleInterface;

class MustImplementRuleInterfaceException extends Exception
{
    public function __construct(string $namespace)
    {
        parent::__construct(sprintf('Class "%s" must implement "%s" interface', $namespace, RuleInterface::class));
    }
}
