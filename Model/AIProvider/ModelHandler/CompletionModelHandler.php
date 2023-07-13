<?php

declare(strict_types=1);

namespace Creatuity\AIContentOpenAI\Model\AIProvider\ModelHandler;

use Creatuity\AIContentOpenAI\Exception\UnsupportedOpenAiModelException;
use Creatuity\AIContentOpenAI\Model\CreatuityOpenAi;
use Magento\Framework\Serialize\Serializer\Json;

class CompletionModelHandler implements ModelHandlerInterface
{
    /**
     * @param string[] $supportedModels
     */
    public function __construct(
        private readonly CreatuityOpenAi $openAi,
        private readonly Json $json,
        private readonly array $supportedModels = [],
        private readonly array $promptOptions = []
    ) {
    }

    public function call(string $model, array $options = [], ?object $stream = null): bool|array
    {
        if (!$this->isApplicable($model)) {
            throw new UnsupportedOpenAiModelException(__('Model %1 is unsupported by %2', $model, static::class));
        }
        $options = array_merge($options, $this->promptOptions);
        $options['model'] = $model;

        return $this->json->unserialize($this->openAi->completion($options, $stream));
    }

    public function isApplicable(string $model): bool
    {
        return in_array($model, $this->supportedModels, true);
    }
}