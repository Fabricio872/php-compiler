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

    /**
     * @return array<int, string>
     */
    public function getRules(): array
    {
        return $this->rules;
    }

    /**
     * @param array<int, string> $rules
     * @return $this
     */
    public function setRules(array $rules): self
    {
        $this->rules = $rules;

        return $this;
    }

    /**
     * @return array<string, string>
     */
    public function getAutoload(): array
    {
        return $this->autoload;
    }

    /**
     * @param array<string, string> $autoload
     * @return $this
     */
    public function setAutoload(array $autoload): self
    {
        $this->autoload = $autoload;

        return $this;
    }
}
