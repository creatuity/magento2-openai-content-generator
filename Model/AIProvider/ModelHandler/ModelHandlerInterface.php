<?php

declare(strict_types=1);

namespace Creatuity\AIContentOpenAI\Model\AIProvider\ModelHandler;

use Creatuity\AIContentOpenAI\Exception\UnsupportedOpenAiModelException;

interface ModelHandlerInterface
{
    /**
     * @param string $model
     * @param array $options
     * @param object|null $stream
     * @return bool|string
     * @throws UnsupportedOpenAiModelException
     * @throws \Exception
     */
    public function call(string $model, array $options = [], ?object $stream = null): bool|string;
    public function isApplicable(string $model): bool;
}