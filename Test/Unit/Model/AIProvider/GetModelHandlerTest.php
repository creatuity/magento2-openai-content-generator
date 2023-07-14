<?php

declare(strict_types=1);

namespace Creatuity\AIContentOpenAI\Test\Unit\Model\AIProvider;

use Creatuity\AIContentOpenAI\Exception\OpenAiModelHandlerNotFoundException;
use Creatuity\AIContentOpenAI\Model\AIProvider\GetModelHandler;
use Creatuity\AIContentOpenAI\Model\AIProvider\ModelHandler\ChatModelHandler;
use Creatuity\AIContentOpenAI\Model\AIProvider\ModelHandler\CompletionModelHandler;
use Creatuity\AIContentOpenAI\Model\AIProvider\ModelHandler\ModelHandlerInterface;
use PHPUnit\Framework\TestCase;

class GetModelHandlerTest extends TestCase
{
    public function testExecute(): void
    {
        $modelName = 'some model name';
        $handlerA = $this->createMock(ModelHandlerInterface::class);
        $handlerA->expects($this->once())->method('isApplicable')->with($modelName)->willReturn(false);
        $handlerB = $this->createMock(ChatModelHandler::class);
        $handlerB->expects($this->once())->method('isApplicable')->with($modelName)->willReturn(false);
        $handlerC = $this->createMock(CompletionModelHandler::class);
        $handlerC->expects($this->once())->method('isApplicable')->with($modelName)->willReturn(true);
        $object = new GetModelHandler([$handlerA, $handlerB, $handlerC]);
        $this->assertSame($handlerC, $object->execute($modelName));
    }

    public function testExecute2(): void
    {
        $modelName = 'some model name';
        $handlerA = $this->createMock(ModelHandlerInterface::class);
        $handlerA->expects($this->once())->method('isApplicable')->with($modelName)->willReturn(false);
        $handlerB = $this->createMock(ChatModelHandler::class);
        $handlerB->expects($this->once())->method('isApplicable')->with($modelName)->willReturn(true);
        $handlerC = $this->createMock(CompletionModelHandler::class);
        $handlerC->expects($this->never())->method('isApplicable');
        $object = new GetModelHandler([$handlerA, $handlerB, $handlerC]);
        $this->assertSame($handlerB, $object->execute($modelName));
    }

    public function testExecuteWithException(): void
    {
        $modelName = 'some model name';
        $handlerA = $this->createMock(ModelHandlerInterface::class);
        $handlerA->expects($this->once())->method('isApplicable')->with($modelName)->willReturn(false);
        $handlerB = $this->createMock(ChatModelHandler::class);
        $handlerB->expects($this->once())->method('isApplicable')->with($modelName)->willReturn(false);
        $handlerC = $this->createMock(CompletionModelHandler::class);
        $handlerC->expects($this->once())->method('isApplicable')->with($modelName)->willReturn(false);
        $this->expectException(OpenAiModelHandlerNotFoundException::class);
        $this->expectExceptionMessage((string) __('Cannot find model handler for "%1"', $modelName));
        $object = new GetModelHandler([$handlerA, $handlerB, $handlerC]);
        $object->execute($modelName);
    }
}