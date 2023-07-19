<?php

declare(strict_types=1);

namespace Creatuity\AIContentOpenAI\Test\Unit\Model\Http;

use Creatuity\AIContentOpenAI\Model\Http\Response\CompletionResponse;
use Magento\Framework\Serialize\Serializer\Json;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CompletionResponseTest extends TestCase
{
    private const RESPONSE_JSON = '{"choices":[{"text":"Test text"}],"error":{"message":"Test error"}}';
    private Json|MockObject $jsonMock;

    protected function setUp(): void
    {
        $this->jsonMock = $this->getMockBuilder(Json::class)
            ->disableOriginalConstructor()
            ->getMock();
    }

    public function testGetText(): void
    {
        $this->jsonMock
            ->expects($this->once())
            ->method('unserialize')
            ->with(self::RESPONSE_JSON)
            ->willReturn(['choices' => [['text' => 'Test text']]]);
        $chatResponse = new CompletionResponse($this->jsonMock, self::RESPONSE_JSON);
        $this->assertSame(['Test text'], $chatResponse->getChoices());
    }

    public function testGetError(): void
    {
        $this->jsonMock
            ->expects($this->once())
            ->method('unserialize')
            ->with(self::RESPONSE_JSON)
            ->willReturn(['error' => ['message' => 'Test error']]);
        $chatResponse = new CompletionResponse($this->jsonMock, self::RESPONSE_JSON);
        $this->assertSame('Test error', $chatResponse->getError());
    }
}