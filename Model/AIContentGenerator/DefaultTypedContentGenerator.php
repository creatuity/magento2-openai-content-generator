<?php

declare(strict_types=1);

namespace Creatuity\AIContentOpenAI\Model\AIContentGenerator;

use Creatuity\AIContent\Api\AITypedContentGeneratorInterface;
use Creatuity\AIContent\Api\Data\AIResponseInterface;
use Creatuity\AIContent\Api\Data\SpecificationInterface;
use Creatuity\AIContent\Exception\UnsupportedContentTypeException;
use Creatuity\AIContent\Model\AIContentGenerator\GenerateContent;
use Creatuity\AIContentOpenAI\Model\DefaultPrompt\PreparePrompt;

class DefaultTypedContentGenerator implements AITypedContentGeneratorInterface
{
    public function __construct(
        private readonly GenerateContent $generateContent,
        private readonly PreparePrompt $preparePrompt,
        private readonly string $type,
        private readonly array $prompt,
        private readonly array $attributes = []
    ) {
    }

    public function execute(SpecificationInterface $specification): AIResponseInterface
    {
        if ($this->type !== $specification->getContentType()) {
            throw new UnsupportedContentTypeException(
                __('"%1" generating is not supported by %2', $this->type, static::class)
            );
        }

        if ($this->attributes && !$specification->getProductAttributes()) {
            $specification->setProductAttributes($this->attributes);
        }

        $prompt = $this->preparePrompt->generate($specification, $this->prompt);

        return $this->generateContent->execute($prompt, $specification);
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function isApplicable(string $contentType): bool
    {
        return $this->type === $contentType;
    }
}