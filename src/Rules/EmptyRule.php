<?php

namespace Rules;

use Fabricio872\PhpCompiler\Rules\RuleInterface;
use Override;
use ReflectionClass;

class EmptyRule implements RuleInterface
{
    #[Override]
    public function isApplicable(ReflectionClass $reflection): bool
    {
        return true;
    }

    #[Override]
    public function apply(ReflectionClass $reflection, string $classData): string
    {
        return $classData;
    }

}
