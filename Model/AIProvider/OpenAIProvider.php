<?php

declare(strict_types=1);

namespace Creatuity\AIContentOpenAI\Model\AIProvider;

use Creatuity\AIContent\Api\AIProviderInterface;
use Creatuity\AIContent\Api\Data\AIRequestInterface;
use Creatuity\AIContent\Api\Data\AIResponseInterface;
use Creatuity\AIContent\Api\Data\AIResponseInterfaceFactory;
use Creatuity\AIContent\Api\Data\SpecificationInterface;
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

    public function call(AIRequestInterface $request, SpecificationInterface $specification): AIResponseInterface
    {
        $modelName = $this->openAiConfig->getModelName();
        $request->setParam('n', $specification->getNumber());
        $choices = $this->getModelHandler->execute($modelName)->call($modelName, $request)->getChoices();

        if (empty($choices)) {
            throw new LocalizedException(__('Failed to generate content using OpenAI. It might be caused by some temporary issue. Please verify your configuration and try again.'));
        }

        return $this->AIResponseInterfaceFactory->create(['data' => [AIResponseInterface::CHOICES_FIELD => $choices]]);
    }

    public function isApplicable(string $name): bool
    {
        return self::NAME === $name;
    }
}