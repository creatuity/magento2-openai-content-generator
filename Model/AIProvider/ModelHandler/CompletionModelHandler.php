<?php

declare(strict_types=1);

namespace Creatuity\AIContentOpenAI\Model\AIProvider\ModelHandler;

use Creatuity\AIContent\Api\Data\AIRequestInterface;
use Creatuity\AIContentOpenAI\Exception\UnsupportedOpenAiModelException;
use Creatuity\AIContentOpenAI\Model\CreatuityOpenAi;
use Creatuity\AIContentOpenAI\Model\Http\Response\CompletionResponseFactory;
use Creatuity\AIContentOpenAI\Model\Http\Response\OpenAiApiResponseInterface;
use Magento\Framework\Exception\LocalizedException;

class CompletionModelHandler implements ModelHandlerInterface
{
    /**
     * @param string[] $supportedModels
     */
    public function __construct(
        private readonly CreatuityOpenAi $openAi,
        private readonly CompletionResponseFactory $completionResponseFactory,
        private readonly array $supportedModels = [],
        private readonly array $promptOptions = []
    ) {
    }

    public function call(string $model, AIRequestInterface $request, ?object $stream = null): OpenAiApiResponseInterface
    {
        if (!$this->isApplicable($model)) {
            throw new UnsupportedOpenAiModelException(__('Model %1 is unsupported by %2', $model, static::class));
        }

        $options = $this->promptOptions;
        $options = array_merge($options, $request->getParams());
        $options['model'] = $model;
        $options['max_tokens'] = $options['max_tokens'] ?? self::MAX_TOKEN_LENGTH;

        $promptMsg = '';
        foreach ($request->getPrompt() as $prompt) {
            $promptMsg .= $prompt . "\n\n";
        }

        $options['max_tokens'] -= (int) (ceil(mb_strlen($promptMsg) / self::AVG_TOKEN_LENGTH));
        $options['prompt'] = $promptMsg;

        $response = $this->completionResponseFactory->create([
            'response' => (string) $this->openAi->completion($options, $stream)
        ]);

        if ($response->getError()) {
            throw new LocalizedException(__($response->getError()));
        }

        return $response;
    }

    public function isApplicable(string $model): bool
    {
        return in_array($model, $this->supportedModels, true);
    }
}