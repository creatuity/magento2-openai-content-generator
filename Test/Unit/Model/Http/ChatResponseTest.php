<?php

declare(strict_types=1);

namespace Creatuity\AIContentOpenAI\Test\Unit\Model\Http\Response;

use Creatuity\AIContentOpenAI\Model\Http\Response\ChatResponse;
use Magento\Framework\Serialize\Serializer\Json;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ChatResponseTest extends TestCase
{
    private const RESPONSE_JSON = '{"choices":[{"message":{"content":"Test content"}}],"error":{"message":"Test error"}}';
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
            ->willReturn(['choices' => [['message' => ['content' => 'Test content']]]]);
        $chatResponse = new ChatResponse($this->jsonMock, self::RESPONSE_JSON);
        $this->assertSame('Test content', $chatResponse->getText());
    }

    public function testGetError(): void
    {
        $this->jsonMock
            ->expects($this->once())
            ->method('unserialize')
            ->with(self::RESPONSE_JSON)
            ->willReturn(['error' => ['message' => 'Test error']]);
        $chatResponse = new ChatResponse($this->jsonMock, self::RESPONSE_JSON);
        $this->assertSame('Test error', $chatResponse->getError());
    }
}
