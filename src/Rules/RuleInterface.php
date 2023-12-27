<?php

declare(strict_types=1);

namespace Fabricio872\PhpCompiler\Rules;

use ReflectionClass;

interface RuleInterface
{
    public function isApplicable(ReflectionClass $reflection): bool;

    public function apply(ReflectionClass $reflection, string $classData): string;
}
