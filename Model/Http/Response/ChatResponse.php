<?php

declare(strict_types=1);

namespace Creatuity\AIContentOpenAI\Model\Http\Response;

use Magento\Framework\Serialize\Serializer\Json;

class ChatResponse implements OpenAiApiResponseInterface
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
            return $data['message']['content'] ?? '';
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