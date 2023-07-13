<?php

declare(strict_types=1);

namespace Creatuity\AIContentOpenAI\Model\AIProvider\ModelHandler;

use Creatuity\AIContentOpenAI\Exception\UnsupportedOpenAiModelException;
use Creatuity\AIContentOpenAI\Model\CreatuityOpenAi;

class ChatModelHandler implements ModelHandlerInterface
{
    /**
     * @param string[] $supportedModels
     */
    public function __construct(
        private readonly CreatuityOpenAi $openAi,
        private readonly array $supportedModels = []
    ) {
    }

    public function call(string $model, array $options = [], ?object $stream = null): bool|string
    {
        if (!$this->isApplicable($model)) {
            throw new UnsupportedOpenAiModelException(__('Model %1 is unsupported by %2', $model, static::class));
        }

        $options['model'] = $model;

        return $this->openAi->chat($options, $stream);
    }

    public function isApplicable(string $model): bool
    {
        return in_array($model, $this->supportedModels, true);
    }
}