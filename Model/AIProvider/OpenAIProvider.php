<?php

declare(strict_types=1);

namespace Creatuity\AIContentOpenAI\Model\AIProvider;

use Creatuity\AIContent\Api\AIProviderInterface;
use Creatuity\AIContent\Api\Data\AIRequestInterface;
use Creatuity\AIContent\Api\Data\AIResponseInterface;
use Creatuity\AIContent\Api\Data\AIResponseInterfaceFactory;
use Creatuity\AIContentOpenAI\Model\Config\OpenAiConfig;
use Magento\Framework\Exception\LocalizedException;

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
        $options = [];
        $options['prompt'] = $request->getInput();
        $response = $this->getModelHandler->execute($modelName)->call($modelName, $options);

        if (!$response || empty($response['choices'][0]['text'])) {
            throw new LocalizedException(__('Failed to generate content using OpenAI. Please verify your configuration and try again.'));
        }

        $text = trim($response['choices'][0]['text']);

        return $this->AIResponseInterfaceFactory->create(['data' => [AIResponseInterface::CONTENT_FIELD => $text]]);
    }

    public function isApplicable(string $name): bool
    {
        return self::NAME === $name;
    }
}