<?php

declare(strict_types=1);

namespace Fabricio872\PhpCompiler\Factories;

use Fabricio872\PhpCompiler\Exceptions\MustImplementRuleInterfaceException;
use Fabricio872\PhpCompiler\Rules\RuleInterface;
use Override;

class RuleFactory implements RuleFactoryInterface
{
    #[Override]
    public function build(string $namespace): RuleInterface
    {
        $rule = new $namespace();
        if (! $rule instanceof RuleInterface) {
            throw new MustImplementRuleInterfaceException($namespace);
        }

        return $rule;
    }
}
