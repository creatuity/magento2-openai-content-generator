<?php

declare(strict_types=1);

namespace Creatuity\AIContentOpenAI\Test\Unit\Model\AIProvider\ModelHandler;

use Creatuity\AIContent\Api\Data\AIRequestInterface;
use Creatuity\AIContentOpenAI\Model\AIProvider\ModelHandler\ChatModelHandler;
use Creatuity\AIContentOpenAI\Model\AIProvider\ModelHandler\CompletionModelHandler;
use Creatuity\AIContentOpenAI\Model\AIProvider\ModelHandler\ModelHandlerInterface;
use Creatuity\AIContentOpenAI\Model\CreatuityOpenAi;
use Creatuity\AIContentOpenAI\Model\DefaultPrompt\Prompt;
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
        $maxTokens = 2500;
        $options = ['options X' => 'some value'];
        $allOptions = array_merge($options, $this->promptOptions);
        $this->expectException(LocalizedException::class);
        $this->expectExceptionMessage('some error');
        $message = 'Some message';
        $promptMsg = $message . "\n\n";
        $allOptions['model'] = $model;
        $allOptions['max_tokens'] = $maxTokens - ((int) (ceil(mb_strlen($promptMsg) / ModelHandlerInterface::AVG_TOKEN_LENGTH)));
        $allOptions['prompt'] = $promptMsg;
        $resultJson = '{"text": "value"}';
        $response = $this->createMock(OpenAiApiResponseInterface::class);
        $response->expects($this->exactly(2))->method('getError')->willReturn('some error');
        $this->openAi->expects($this->once())->method('completion')->with($allOptions, null)->willReturn($resultJson);
        $this->chatResponseFactory->expects($this->once())->method('create')->with(['response' => $resultJson])->willReturn($response);

        $prompt = $this->createMock(Prompt::class);
        $prompt->expects($this->once())->method('__toString')->willReturn($message);

        $request = $this->createMock(AIRequestInterface::class);
        $request->expects($this->once())->method('getParams')->willReturn(array_merge($options, ['max_tokens' => $maxTokens]));
        $request->expects($this->once())->method('getPrompt')->willReturn([$prompt]);

        $this->getObject()->call('model a', $request);
    }

    public function testCallSuccess(): void
    {
        $model = 'model c';
        $maxTokens = 2500;
        $message = 'Some message';
        $promptMsg = $message . "\n\n";
        $options = ['options X' => 'some value'];
        $allOptions = array_merge($options, $this->promptOptions);
        $allOptions['model'] = $model;
        $allOptions['max_tokens'] = $maxTokens - ((int) (ceil(mb_strlen($promptMsg) / ModelHandlerInterface::AVG_TOKEN_LENGTH)));
        $allOptions['prompt'] = $promptMsg;
        $resultJson = '{"text": "value"}';
        $response = $this->createMock(OpenAiApiResponseInterface::class);
        $response->expects($this->once())->method('getError')->willReturn('');
        $this->openAi->expects($this->once())->method('completion')->with($allOptions, null)->willReturn($resultJson);
        $this->chatResponseFactory->expects($this->once())->method('create')->with(['response' => $resultJson])->willReturn($response);

        $prompt = $this->createMock(Prompt::class);
        $prompt->expects($this->once())->method('__toString')->willReturn($message);

        $request = $this->createMock(AIRequestInterface::class);
        $request->expects($this->once())->method('getParams')->willReturn(array_merge($options, ['max_tokens' => $maxTokens]));
        $request->expects($this->once())->method('getPrompt')->willReturn([$prompt]);

        $this->assertSame($response, $this->getObject()->call($model, $request));
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