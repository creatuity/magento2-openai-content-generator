<?php

declare(strict_types=1);

namespace Creatuity\AIContentOpenAI\Model\AIProvider;

use Creatuity\AIContent\Api\AIProviderInterface;
use Creatuity\AIContent\Api\Data\AIRequestInterface;
use Creatuity\AIContent\Api\Data\AIResponseInterface;
use Creatuity\AIContent\Api\Data\AIResponseInterfaceFactory;
use Creatuity\AIContentOpenAI\Model\Config\OpenAiConfig;

class OpenAIProvider implements AIProviderInterface
{
    public const NAME = 'openai';

    public function __construct(
        private readonly OpenAiConfig $openAiConfig,
        private readonly GetModelHandler $getModelHandler,
        private readonly AIResponseInterfaceFactory $AIResponseInterfaceFactory
    ) {
    }

    public function call(AIRequestInterface $request): AIResponseInterface
    {
        $modelName = $this->openAiConfig->getModelName();
        $this->getModelHandler->execute($modelName)->call($modelName);

        return $this->AIResponseInterfaceFactory->create();
    }

    public function isApplicable(string $name): bool
    {
        return self::NAME === $name;
    }
}