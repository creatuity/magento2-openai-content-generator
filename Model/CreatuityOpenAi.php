<?php

declare(strict_types=1);

namespace Creatuity\AIContentOpenAI\Model;

use Creatuity\AIContentOpenAI\Model\Config\OpenAiContentApiKey;
use Orhanerday\OpenAi\OpenAi;

class CreatuityOpenAi extends OpenAi
{
    public function __construct(OpenAiContentApiKey $aiContentApiKey)
    {
        parent::__construct((string) $aiContentApiKey);
    }
}