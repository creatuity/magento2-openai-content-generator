<?php

declare(strict_types=1);

namespace Creatuity\AIContentOpenAI\Model\AIProvider\ModelHandler;

use Creatuity\AIContent\Api\Data\AIRequestInterface;
use Creatuity\AIContentOpenAI\Exception\UnsupportedOpenAiModelException;
use Creatuity\AIContentOpenAI\Model\CreatuityOpenAi;
use Creatuity\AIContentOpenAI\Model\Http\Response\ChatResponseFactory;
use Creatuity\AIContentOpenAI\Model\Http\Response\OpenAiApiResponseInterface;
use Magento\Framework\Exception\LocalizedException;

class ChatModelHandler implements ModelHandlerInterface
{
    /**
     * @param string[] $supportedModels
     */
    public function __construct(
        private readonly CreatuityOpenAi $openAi,
        private readonly ChatResponseFactory $chatResponseFactory,
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
        $options['model'] = $model;
        $options = array_merge($options, $request->getParams());
        $options['messages'] = [];
        foreach ($request->getPrompt() as $prompt) {
            $options['messages'][] = $prompt->getInput();
        }
        $options['messages'] = array_merge([], ...$options['messages']);

        array_walk_recursive($options, function (&$val) {
            if (is_numeric($val)) {
                $val = (float) $val;
            }
        });

        $response = $this->chatResponseFactory->create([
            'response' => (string) $this->openAi->chat($options, $stream)
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