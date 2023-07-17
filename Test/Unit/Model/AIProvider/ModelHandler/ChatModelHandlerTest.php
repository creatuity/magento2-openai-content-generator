<?php

declare(strict_types=1);

namespace Creatuity\AIContentOpenAI\Test\Unit\Model\AIProvider\ModelHandler;

use Creatuity\AIContentOpenAI\Exception\UnsupportedOpenAiModelException;
use Creatuity\AIContentOpenAI\Model\AIProvider\ModelHandler\ChatModelHandler;
use Creatuity\AIContentOpenAI\Model\CreatuityOpenAi;
use Creatuity\AIContentOpenAI\Model\Http\Response\ChatResponseFactory;
use Creatuity\AIContentOpenAI\Model\Http\Response\OpenAiApiResponseInterface;
use Magento\Framework\Exception\LocalizedException;
use PHPUnit\Framework\TestCase;

class ChatModelHandlerTest extends TestCase
{
    private readonly CreatuityOpenAi $openAi;
    private readonly ChatResponseFactory $chatResponseFactory;
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
        $this->chatResponseFactory = $this->getMockBuilder(ChatResponseFactory::class)
            ->disableOriginalConstructor()->setMethods(['create'])->getMock();
    }

    public function testCallWrongModel(): void
    {
        $model = 'unsupported model name';
        $this->expectException(UnsupportedOpenAiModelException::class);
        $this->expectExceptionMessage((string) __('Model %1 is unsupported by %2', $model, ChatModelHandler::class));
        $this->getObject()->call($model);
    }

    public function testCallSuccess(): void
    {
        $model = 'model c';
        $options = ['prompt' => 'some text'];
        $allOptions = array_merge($options, $this->promptOptions);
        array_walk_recursive($allOptions, function (&$val) {
            if (is_numeric($val)) {
                $val = (float) $val;
            }
        });
        $allOptions['model'] = $model;
        $allOptions['messages'] = [
            [
                'role' => 'user',
                'content' => $options['prompt']
            ]
        ];
        unset($allOptions['prompt']);
        $resultJson = '{"text": "value"}';
        $response = $this->createMock(OpenAiApiResponseInterface::class);
        $response->expects($this->once())->method('getError')->willReturn('');
        $this->openAi->expects($this->once())->method('chat')->with($allOptions, null)->willReturn($resultJson);
        $this->chatResponseFactory->expects($this->once())->method('create')->with(['response' => $resultJson])->willReturn($response);
        $this->assertSame($response, $this->getObject()->call($model, $options));
    }

    public function testCallError(): void
    {
        $allOptions['model'] = 'model a';
        $allOptions['messages'] = [
            [
                'role' => 'user',
                'content' => 'some text'
            ]
        ];
        $allOptions = array_merge($allOptions, $this->promptOptions);
        $resultJson = '{"text": "value"}';
        $response = $this->createMock(OpenAiApiResponseInterface::class);
        $response->expects($this->exactly(2))->method('getError')->willReturn('some error');
        $this->openAi->expects($this->once())->method('chat')->with($allOptions, null)->willReturn($resultJson);
        $this->chatResponseFactory->expects($this->once())->method('create')->with(['response' => $resultJson])->willReturn($response);
        $this->expectException(LocalizedException::class);
        $this->expectExceptionMessage('some error');
        $allOptions['prompt'] = 'some text';
        $this->getObject()->call('model a', $allOptions);
    }

    public function getObject(): ChatModelHandler
    {
        return new ChatModelHandler(
            $this->openAi,
            $this->chatResponseFactory,
            $this->supportedModels,
            $this->promptOptions
        );
    }
}