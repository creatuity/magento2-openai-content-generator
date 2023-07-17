<?php

declare(strict_types=1);

namespace Creatuity\AIContentOpenAI\Test\Unit\Model\Config;

use Creatuity\AIContentOpenAI\Model\Config\OpenAiConfig;
use Creatuity\AIContentOpenAI\Model\Config\OpenAiContentApiKey;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class OpenAiContentApiKeyTest extends TestCase
{
    private readonly OpenAiConfig|MockObject $openAiConfig;

    protected function setUp(): void
    {
        $this->openAiConfig = $this->createMock(OpenAiConfig::class);
    }

    public function test__ToString(): void
    {
        $key = 'key value';
        $this->openAiConfig->expects($this->once())->method('getOpenAiApiKey')->willReturn($key);
        $this->assertSame($key, (new OpenAiContentApiKey($this->openAiConfig))->__toString());
    }
}