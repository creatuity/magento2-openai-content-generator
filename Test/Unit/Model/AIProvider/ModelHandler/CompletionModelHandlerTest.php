<?php

declare(strict_types=1);

namespace Creatuity\AIContentOpenAI\Test\Unit\Model\AIProvider\ModelHandler;

use Creatuity\AIContentOpenAI\Model\AIProvider\ModelHandler\ChatModelHandler;
use Creatuity\AIContentOpenAI\Model\AIProvider\ModelHandler\CompletionModelHandler;
use Creatuity\AIContentOpenAI\Model\AIProvider\ModelHandler\ModelHandlerInterface;
use Creatuity\AIContentOpenAI\Model\CreatuityOpenAi;
use Creatuity\AIContentOpenAI\Model\Http\Response\CompletionResponseFactory;
use Creatuity\AIContentOpenAI\Model\Http\Response\OpenAiApiResponseInterface;
use Magento\Framework\Exception\LocalizedException;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class CompletionModelHandlerTest extends TestCase
{
    private readonly CreatuityOpenAi|MockObject $openAi;
    private readonly CompletionResponseFactory|MockObject $completionResponseFactory;
    private array $supportedModels = [
        'model a',
        'model b',
        'model c'
    ];

    private array $promptOptions = [
        'option a' => 1,
        'option b' => '2',
        'option c' => '0',
        'options d' => 'some value'
    ];

    protected function setUp(): void
    {
        $this->openAi = $this->createMock(CreatuityOpenAi::class);
        $this->chatResponseFactory = $this->getMockBuilder(CompletionResponseFactory::class)
            ->disableOriginalConstructor()->setMethods(['create'])->getMock();
    }

    public function testCallError(): void
    {
        $model = 'model a';
        $options = ['prompt' => 'some text', 'options X' => 'some value', 'max_tokens' => 2500];
        $allOptions = array_merge($options, $this->promptOptions);
        $this->expectException(LocalizedException::class);
        $this->expectExceptionMessage('some error');
        $allOptions['model'] = $model;
        $allOptions['max_tokens'] = $options['max_tokens'] - (int) ceil(mb_strlen($options['prompt']) / ModelHandlerInterface::AVG_TOKEN_LENGTH);
        $resultJson = '{"text": "value"}';
        $response = $this->createMock(OpenAiApiResponseInterface::class);
        $response->expects($this->exactly(2))->method('getError')->willReturn('some error');
        $this->openAi->expects($this->once())->method('completion')->with($allOptions, null)->willReturn($resultJson);
        $this->chatResponseFactory->expects($this->once())->method('create')->with(['response' => $resultJson])->willReturn($response);
        $this->getObject()->call('model a', $options);
    }

    public function testCallSuccess(): void
    {
        $model = 'model c';
        $options = ['prompt' => 'some text', 'options X' => 'some value', 'max_tokens' => 4000];
        $allOptions = array_merge($options, $this->promptOptions);
        $allOptions['model'] = $model;
        $allOptions['max_tokens'] = $options['max_tokens'] - (int) ceil(mb_strlen($options['prompt']) / ModelHandlerInterface::AVG_TOKEN_LENGTH);
        $resultJson = '{"text": "value"}';
        $response = $this->createMock(OpenAiApiResponseInterface::class);
        $response->expects($this->once())->method('getError')->willReturn('');
        $this->openAi->expects($this->once())->method('completion')->with($allOptions, null)->willReturn($resultJson);
        $this->chatResponseFactory->expects($this->once())->method('create')->with(['response' => $resultJson])->willReturn($response);
        $this->assertSame($response, $this->getObject()->call($model, $options));
    }

    public function getObject(): CompletionModelHandler
    {
        return new CompletionModelHandler(
            $this->openAi,
            $this->chatResponseFactory,
            $this->supportedModels,
            $this->promptOptions
        );
    }
}