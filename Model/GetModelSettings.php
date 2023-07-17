<?php

declare(strict_types=1);

namespace Creatuity\AIContentOpenAI\Model;

use Creatuity\AIContentOpenAI\Model\AIProvider\ModelHandler\ModelHandlerInterface;

class GetModelSettings
{
    public function __construct(
        private readonly array $settings = []
    ) {
    }

    /**
     * @return string[]
     */
    public function getModelNames(): array
    {
        return array_keys($this->settings);
    }

    public function getMaxTokens(string $model): int
    {
        return (int) ($this->settings[$model]['max_length'] ?? ModelHandlerInterface::MAX_TOKEN_LENGTH);
    }
}