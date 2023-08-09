<?php

declare(strict_types=1);

namespace Creatuity\AIContentOpenAI\Model\AIProvider\ModelHandler;

use Creatuity\AIContent\Api\Data\AIRequestInterface;
use Creatuity\AIContentOpenAI\Exception\UnsupportedOpenAiModelException;
use Creatuity\AIContentOpenAI\Model\Http\Response\OpenAiApiResponseInterface;
use Magento\Framework\Exception\LocalizedException;

interface ModelHandlerInterface
{
    public const MAX_TOKEN_LENGTH = 4000;
    public const DEFAULT_TEMPERATURE = 1;
    public const AVG_TOKEN_LENGTH = 4;

    /**
     * @param string $model
     * @param AIRequestInterface $request
     * @param object|null $stream
     * @throws UnsupportedOpenAiModelException
     * @throws LocalizedException
     * @throws \Exception
     */
    public function call(string $model, AIRequestInterface $request, ?object $stream = null): OpenAiApiResponseInterface;
    public function isApplicable(string $model): bool;
}