<?php

declare(strict_types=1);

namespace Creatuity\AIContentOpenAI\Model\Http\Response;

use Magento\Framework\Exception\LocalizedException;

interface OpenAiApiResponseInterface
{
    /**
     * @throws LocalizedException
     * @return string[]
     */
    public function getChoices(): array;

    public function getError(): ?string;
}