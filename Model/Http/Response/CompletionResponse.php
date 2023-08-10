<?php

declare(strict_types=1);

namespace Creatuity\AIContentOpenAI\Model\Http\Response;

use Magento\Framework\Serialize\Serializer\Json;

class CompletionResponse implements OpenAiApiResponseInterface
{
    public function __construct(
        private readonly Json $json,
        private readonly string $response
    ) {
    }

    public function getChoices(): array
    {
        $choices = $this->toArray()['choices'] ?? [];

        return array_filter(array_map(function ($data) {
            preg_match('/<response>(.*)<\/response>/', $data['text'] ?? '', $matches);

            return html_entity_decode($matches[1] ?? $data['text']);
        }, $choices));
    }

    public function getError(): ?string
    {
        return $this->toArray()['error']['message'] ?? null;
    }

    private function toArray(): array
    {
        return $this->json->unserialize($this->response);
    }
}