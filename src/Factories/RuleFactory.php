<?php

namespace Fabricio872\PhpCompiler\Factories;

use Fabricio872\PhpCompiler\Rules\RuleInterface;
use Override;

class RuleFactory implements RuleFactoryInterface
{
    #[Override]
    public function build(string $namespace): RuleInterface
    {
        return new $namespace();
    }
}
