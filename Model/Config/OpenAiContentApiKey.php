<?php

declare(strict_types=1);

namespace Creatuity\AIContentOpenAI\Model\Config;

use Stringable;

class OpenAiContentApiKey implements Stringable
{
    public function __construct(
        private readonly OpenAiConfig $openAiConfig
    ) {
    }

    public function __toString(): string
    {
        return (string) $this->openAiConfig->getOpenAiApiKey();
    }
}