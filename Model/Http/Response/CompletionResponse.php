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

    public function getText(): string
    {
        return $this->toArray()['choices'][0]['text'] ?? '';
    }

    private function toArray(): array
    {
        return $this->json->unserialize($this->response);
    }
}