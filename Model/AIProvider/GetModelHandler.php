<?php

declare(strict_types=1);

namespace Creatuity\AIContentOpenAI\Model\AIProvider;

use Creatuity\AIContentOpenAI\Exception\OpenAiModelHandlerNotFoundException;
use Creatuity\AIContentOpenAI\Model\AIProvider\ModelHandler\ModelHandlerInterface;

class GetModelHandler
{
    /**
     * @param ModelHandlerInterface[] $handlers
     */
    public function __construct(
        private readonly array $handlers = []
    ) {
    }

    /**
     * @throws OpenAiModelHandlerNotFoundException
     */
    public function execute(string $modelName): ModelHandlerInterface
    {
        foreach ($this->handlers as $handler) {
            if ($handler->isApplicable($modelName)) {
                return $handler;
            }
        }

        throw new OpenAiModelHandlerNotFoundException(__('Cannot find model handler for "%1"', $modelName));
    }
}