<?php

declare(strict_types=1);

namespace Creatuity\AIContentOpenAI\Test\Unit\Model\Config;

use Creatuity\AIContentOpenAI\Model\Config\OpenAiConfig;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Encryption\EncryptorInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class OpenAiConfigTest extends TestCase
{
    private readonly ScopeConfigInterface|MockObject $scopeConfig;
    private readonly EncryptorInterface|MockObject $encryptor;

    public function setUp(): void
    {
        $this->scopeConfig = $this->createMock(ScopeConfigInterface::class);
        $this->encryptor = $this->createMock(EncryptorInterface::class);
    }

    public function testGetOpenAiApiKey(): void
    {
        $key = 'some key';
        $decryptedKey = 'decrypted key';
        $this->scopeConfig->expects($this->once())->method('getValue')->with(OpenAiConfig::XML_PATH_OPENAI_API_KEY)->willReturn($key);
        $this->encryptor->expects($this->once())->method('decrypt')->with($key)->willReturn($decryptedKey);
        $this->assertSame($decryptedKey, (new OpenAiConfig($this->scopeConfig, $this->encryptor))->getOpenAiApiKey());
    }

    public function testGetModelName(): void
    {
        $model = 'some model name';
        $this->scopeConfig->expects($this->once())->method('getValue')->with(OpenAiConfig::XML_PATH_OPENAI_MODEL_NAME)->willReturn($model);
        $this->assertSame($model, (new OpenAiConfig($this->scopeConfig, $this->encryptor))->getModelName());
    }
}