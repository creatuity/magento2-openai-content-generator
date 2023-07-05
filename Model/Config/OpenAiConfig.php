<?php

declare(strict_types=1);

namespace Creatuity\AIContentOpenAI\Model\Config;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Encryption\EncryptorInterface;

class OpenAiConfig
{
    public const XML_PATH_OPENAI_API_KEY = 'creatuityaicontent/openai_api/api_key';
    public const XML_PATH_OPENAI_MODEL_NAME = 'creatuityaicontent/openai_api/model_name';

    public function __construct(
        private readonly ScopeConfigInterface $scopeConfig,
        private readonly EncryptorInterface $encryptor
    ) {
    }

    public function getOpenAiApiKey(): string
    {
        $key = (string) $this->scopeConfig->getValue(self::XML_PATH_OPENAI_API_KEY);

        return $this->encryptor->decrypt($key);
    }

    public function getModelName(): string
    {
        return (string) $this->scopeConfig->getValue(self::XML_PATH_OPENAI_MODEL_NAME);
    }
}