<?php

declare(strict_types=1);

namespace Creatuity\AIContentOpenAI\Model\AIProvider\ModelHandler;

use Creatuity\AIContentOpenAI\Exception\UnsupportedOpenAiModelException;

interface ModelHandlerInterface
{
    public const MAX_TOKEN_LENGTH = 4000;
    public const DEFAULT_TEMPERATURE = 1;

    /**
     * @param string $model
     * @param array $options
     * @param object|null $stream
     * @return bool|array
     * @throws UnsupportedOpenAiModelException
     * @throws \Exception
     */
    public function call(string $model, array $options = [], ?object $stream = null): bool|array;
    public function isApplicable(string $model): bool;
}