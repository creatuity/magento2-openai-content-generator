<?php

declare(strict_types=1);

namespace Creatuity\AIContentOpenAI\Test\Unit\Model\DefaultPrompt;

use Creatuity\AIContent\Api\Data\SpecificationInterface;
use Creatuity\AIContentOpenAI\Model\DefaultPrompt\PreparePrompt;
use Creatuity\AIContentOpenAI\Model\DefaultPrompt\Prompt;
use Creatuity\AIContentOpenAI\Model\DefaultPrompt\PromptBuilder;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class PreparePromptTest extends TestCase
{
    private readonly PromptBuilder|MockObject $promptBuilder;

    protected function setUp(): void
    {
        $this->promptBuilder = $this->createMock(PromptBuilder::class);
    }

    public function testGenerate(): void
    {
        $message = [
            Prompt::SYSTEM_ROLE => 'msg 1',
            Prompt::USER_ROLE => 'msg 2',
            Prompt::ASSISTANT_ROLE => 'msg 3',
        ];
        $messages = [$message];
        $specification = $this->createMock(SpecificationInterface::class);
        $prompt = $this->createMock(Prompt::class);
        $this->promptBuilder->expects($this->exactly(count($messages)))->method('build')->with($message)->willReturn($prompt);
        $object = new PreparePrompt($this->promptBuilder);
        $this->assertSame([$prompt], $object->generate($specification, $messages));
    }
}