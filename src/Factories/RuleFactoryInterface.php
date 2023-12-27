<?php

namespace Fabricio872\PhpCompiler\Factories;

use Fabricio872\PhpCompiler\Rules\RuleInterface;

interface RuleFactoryInterface
{
    public function build(string $namespace): RuleInterface;
}
