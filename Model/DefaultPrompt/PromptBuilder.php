<?php

declare(strict_types=1);

namespace Creatuity\AIContentOpenAI\Model\DefaultPrompt;

use Creatuity\AIContent\Api\Data\PromptUserMsgProviderInterface;
use Creatuity\AIContent\Api\Data\SpecificationInterface;

class PromptBuilder
{
    public function __construct(
        private readonly PromptFactory $promptFactory,
        private readonly PromptUserMsgProviderInterface $promptUserMsgProvider
    ) {
    }

    public function build(array $prompt, SpecificationInterface $specification): Prompt
    {
        $placeholders = [
            'max_len' => $specification->getMaxLength() ?: SpecificationInterface::MAX_LENGTH,
            'min_len' => $specification->getMinLength() ?: 0
        ];

        return $this->promptFactory->create([
            Prompt::SYSTEM_ROLE => __($prompt[Prompt::SYSTEM_ROLE] ?? '', $placeholders),
            Prompt::USER_ROLE => $this->promptUserMsgProvider->execute($specification),
            Prompt::ASSISTANT_ROLE => __($prompt[Prompt::ASSISTANT_ROLE] ?? '', $placeholders),
        ]);
    }
}