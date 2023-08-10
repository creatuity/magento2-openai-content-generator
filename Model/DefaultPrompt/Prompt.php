<?php

declare(strict_types=1);

namespace Creatuity\AIContentOpenAI\Model\DefaultPrompt;

use Creatuity\AIContent\Api\Data\PromptInterface;

class Prompt implements PromptInterface
{
    public const SYSTEM_ROLE = 'system';
    public const USER_ROLE = 'user';
    public const ASSISTANT_ROLE = 'assistant';

    public function __construct(
        private readonly string $system = '',
        private readonly string $user = '',
        private readonly string $assistant = ''
    ) {
    }

    public function getInput(): array
    {
        $input = [];
        foreach ($this->getData() as $role => $content) {
            $input[] = ['role' => $role, 'content' => $content];
        }

        return $input;
    }

    public function __toString(): string
    {
        return implode("\n", $this->getData());
    }

    public function getData(): array
    {
        return array_filter([
            self::SYSTEM_ROLE => $this->system,
            self::USER_ROLE => $this->user,
            self::ASSISTANT_ROLE => $this->assistant
        ]);
    }
}