<?php

declare(strict_types=1);

namespace Creatuity\AIContentOpenAI\Test\Unit\Model\AIProvider;

use Creatuity\AIContent\Api\Data\AIRequestInterface;
use Creatuity\AIContent\Api\Data\AIResponseInterface;
use Creatuity\AIContent\Api\Data\AIResponseInterfaceFactory;
use Creatuity\AIContent\Api\Data\SpecificationInterface;
use Creatuity\AIContentOpenAI\Model\AIProvider\GetModelHandler;
use Creatuity\AIContentOpenAI\Model\AIProvider\ModelHandler\ModelHandlerInterface;
use Creatuity\AIContentOpenAI\Model\AIProvider\OpenAIProvider;
use Creatuity\AIContentOpenAI\Model\Config\OpenAiConfig;
use Creatuity\AIContentOpenAI\Model\DefaultPrompt\Prompt;
use Creatuity\AIContentOpenAI\Model\Http\Response\OpenAiApiResponseInterface;
use Magento\Framework\Exception\LocalizedException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class OpenAIProviderTest extends TestCase
{
    private readonly OpenAiConfig|MockObject $openAiConfig;
    private readonly GetModelHandler|MockObject $getModelHandler;
    private readonly AIResponseInterfaceFactory|MockObject $AIResponseInterfaceFactory;

    protected function setUp(): void
    {
        $this->openAiConfig = $this->createMock(OpenAiConfig::class);
        $this->getModelHandler = $this->createMock(GetModelHandler::class);
        $this->AIResponseInterfaceFactory = $this->getMockBuilder(AIResponseInterfaceFactory::class)
            ->disableOriginalConstructor()
            ->setMethods(['create'])
            ->getMock();
    }

    public function testCall(): void
    {
        $modelName = 'model name';
        $responseText = ['some api response'];
        $this->openAiConfig->expects($this->once())->method('getModelName')->willReturn($modelName);
        $request = $this->createMock(AIRequestInterface::class);
        $request->expects($this->once())->method('setParam')->with('n', 3);
        $handler = $this->createMock(ModelHandlerInterface::class);
        $spec = $this->createMock(SpecificationInterface::class);
        $spec->expects($this->once())->method('getNumber')->willReturn(3);
        $apiResponse = $this->createMock(OpenAiApiResponseInterface::class);
        $apiResponse->expects($this->once())->method('getChoices')->willReturn($responseText);
        $handler->expects($this->once())->method('call')->with($modelName, $request)->willReturn($apiResponse);
        $this->getModelHandler->expects($this->once())->method('execute')->with($modelName)->willReturn($handler);
        $result = $this->createMock(AIResponseInterface::class);
        $this->AIResponseInterfaceFactory
            ->expects($this->once())
            ->method('create')
            ->with(['data' => [AIResponseInterface::CHOICES_FIELD => $responseText]])
            ->willReturn($result);
        $this->assertSame($result, $this->getObject()->call($request, $spec));
    }

    public function testCallException(): void
    {
        $modelName = 'model name';
        $responseText = [];
        $this->openAiConfig->expects($this->once())->method('getModelName')->willReturn($modelName);
        $spec = $this->createMock(SpecificationInterface::class);
        $spec->expects($this->once())->method('getNumber')->willReturn(3);
        $request = $this->createMock(AIRequestInterface::class);
        $request->expects($this->once())->method('setParam')->with('n', 3);
        $handler = $this->createMock(ModelHandlerInterface::class);
        $apiResponse = $this->createMock(OpenAiApiResponseInterface::class);
        $apiResponse->expects($this->once())->method('getChoices')->willReturn($responseText);
        $handler->expects($this->once())->method('call')->with($modelName, $request)->willReturn($apiResponse);
        $this->getModelHandler->expects($this->once())->method('execute')->with($modelName)->willReturn($handler);
        $this->expectException(LocalizedException::class);
        $this->expectExceptionMessage((string) __('Failed to generate content using OpenAI. It might be caused by some temporary issue. Please verify your configuration and try again.'));
        $this->AIResponseInterfaceFactory->expects($this->never())->method('create');
        $this->getObject()->call($request, $spec);
    }

    private function getObject(): OpenAIProvider
    {
        return new OpenAIProvider(
            $this->openAiConfig,
            $this->getModelHandler,
            $this->AIResponseInterfaceFactory
        );
    }
}