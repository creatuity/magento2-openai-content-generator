<?php

declare(strict_types=1);

namespace Creatuity\AIContentOpenAI\Model\DefaultPrompt;

use Creatuity\AIContent\Api\Data\PromptInterface;
use Creatuity\AIContent\Api\Data\SpecificationInterface;

class PreparePrompt
{
    public function __construct(
        private readonly PromptBuilder $promptBuilder
    ) {
    }

    /**
     * @param SpecificationInterface $specification
     * @param string[][] $promptMessages
     * @return PromptInterface[]
     */
    public function generate(SpecificationInterface $specification, array $promptMessages): array
    {
        $prompt = [];
        foreach ($promptMessages as $promptMessage) {
            $prompt[] = $this->promptBuilder->build($promptMessage, $specification);
        }

        return $prompt;
    }
}