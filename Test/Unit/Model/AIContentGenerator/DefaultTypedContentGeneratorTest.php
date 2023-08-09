<?php

declare(strict_types=1);

namespace Creatuity\AIContentOpenAI\Test\Unit\Model\AIContentGenerator;

use Creatuity\AIContent\Api\Data\AIResponseInterface;
use Creatuity\AIContent\Api\Data\SpecificationInterface;
use Creatuity\AIContent\Exception\UnsupportedContentTypeException;
use Creatuity\AIContent\Model\AIContentGenerator\GenerateContent;
use Creatuity\AIContentOpenAI\Model\AIContentGenerator\DefaultTypedContentGenerator;
use Creatuity\AIContentOpenAI\Model\DefaultPrompt\PreparePrompt;
use Creatuity\AIContentOpenAI\Model\DefaultPrompt\Prompt;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class DefaultTypedContentGeneratorTest extends TestCase
{
    private readonly GenerateContent|MockObject $generateContent;
    private readonly PreparePrompt|MockObject $preparePrompt;

    protected function setUp(): void
    {
        $this->generateContent = $this->createMock(GenerateContent::class);
        $this->preparePrompt = $this->createMock(PreparePrompt::class);
    }

    /**
     * @dataProvider executeDataProvider
     */
    public function testExecuteNoSpecificationAttributes(string $type): void
    {
        $attrs = ['color', 'size'];
        $specAttributes = [];
        $specification = $this->createMock(SpecificationInterface::class);
        $specification->expects($this->atLeast(1))->method('getContentType')->willReturn($type);
        $specification->expects($this->once())->method('getProductAttributes')->willReturn($specAttributes);
        $specification->expects($this->once())->method('setProductAttributes');
        $prompt = $this->createMock(Prompt::class);
        $apiResponse = $this->createMock(AIResponseInterface::class);
        $this->preparePrompt->expects($this->once())->method('generate')->with($specification, [$this->getMessage()])->willReturn([$prompt]);
        $this->generateContent->expects($this->once())->method('execute')->with([$prompt])->willReturn($apiResponse);
        $object = $this->getObject($type, $attrs);
        $result = $object->execute($specification);
        $this->assertSame($apiResponse, $result);
    }

    /**
     * @dataProvider executeDataProvider
     */
    public function testExecuteWithSpecificationAttributes(string $type): void
    {
        $attrs = ['color', 'size'];
        $specAttributes = ['color', 'size', 'name'];
        $specification = $this->createMock(SpecificationInterface::class);
        $specification->expects($this->atLeast(1))->method('getContentType')->willReturn($type);
        $specification->expects($this->once())->method('getProductAttributes')->willReturn($specAttributes);
        $specification->expects($this->never())->method('setProductAttributes');
        $apiResponse = $this->createMock(AIResponseInterface::class);
        $prompt = $this->createMock(Prompt::class);
        $this->preparePrompt->expects($this->once())->method('generate')->with($specification, [$this->getMessage()])->willReturn([$prompt]);
        $this->generateContent->expects($this->once())->method('execute')->with([$prompt])->willReturn($apiResponse);
        $object = $this->getObject($type, $attrs);
        $result = $object->execute($specification);
        $this->assertSame($apiResponse, $result);
    }

    /**
     * @dataProvider executeDataProvider
     */
    public function testExecuteExceptionExpected(string $type): void
    {
        $this->expectException(UnsupportedContentTypeException::class);
        $specification = $this->createMock(SpecificationInterface::class);
        $this->preparePrompt->expects($this->never())->method('generate');
        $this->generateContent->expects($this->never())->method('execute');
        $this->getObject('other type')->execute($specification);
    }

    public function testGetType(): void
    {
        $type = 'description';
        $this->assertSame($type, $this->getObject($type)->getType());
    }

    /**
     * @dataProvider isApplicableDataProvider
     */
    public function testIsApplicable(string $type, string $typeToCompare, bool $expected): void
    {
        $this->assertSame($expected, $this->getObject($type)->isApplicable($typeToCompare));
    }

    private function isApplicableDataProvider(): array
    {
        return [
            ['description', 'description', true],
            ['description', 'meta-title', false]
        ];
    }

    private function executeDataProvider(): array
    {
        return [
            ['description'],
            ['meta-title']
        ];
    }

    private function getObject(string $type, array $attributes = []): DefaultTypedContentGenerator
    {
        return new DefaultTypedContentGenerator(
            $this->generateContent,
            $this->preparePrompt,
            $type,
            [$this->getMessage()],
            $attributes
        );
    }

    private function getMessage(): array
    {
        return [
            Prompt::SYSTEM_ROLE => 'msg 1',
            Prompt::USER_ROLE => 'msg 2',
            Prompt::ASSISTANT_ROLE => 'msg 3'
        ];
    }
}