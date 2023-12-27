<?php

declare(strict_types=1);

namespace Fabricio872\PhpCompiler\Model;

class Config
{
    private string $compiledSrc = '/compiled';

    /** @var array <int, string> */
    private array $rules = [];

    /** @var array <string, string> */
    private array $autoload = [];

    public function getCompiledSrc(): string
    {
        return $this->compiledSrc;
    }

    public function setCompiledSrc(string $compiledSrc): void
    {
        $this->compiledSrc = $compiledSrc;
    }

    public function getRules(): array
    {
        return $this->rules;
    }

    public function setRules(array $rules): self
    {
        $this->rules = $rules;

        return $this;
    }

    public function getAutoload(): array
    {
        return $this->autoload;
    }

    public function setAutoload(array $autoload): self
    {
        $this->autoload = $autoload;

        return $this;
    }
}
