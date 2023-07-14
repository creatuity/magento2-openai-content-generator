<?php

declare(strict_types=1);

namespace Creatuity\AIContentOpenAI\Test\Unit\Model;

use Creatuity\AIContentOpenAI\Model\AIProvider\ModelHandler\ModelHandlerInterface;
use Creatuity\AIContentOpenAI\Model\GetModelSettings;
use PHPUnit\Framework\TestCase;

class GetModelSettingsTest extends TestCase
{
    private const SETTINGS = [
        'model1' => ['max_length' => 150],
        'model2' => ['max_length' => 200],
    ];

    private GetModelSettings $getModelSettings;

    protected function setUp(): void
    {
        $this->getModelSettings = new GetModelSettings(
            self::SETTINGS
        );
    }

    public function testGetModelNames(): void
    {
        $expectedModels = ['model1', 'model2'];

        $this->assertSame($expectedModels, $this->getModelSettings->getModelNames());
    }

    public function testGetMaxTokens(): void
    {
        $this->assertSame(150, $this->getModelSettings->getMaxTokens('model1'));
        $this->assertSame(200, $this->getModelSettings->getMaxTokens('model2'));
    }

    public function testGetMaxTokensWithNoSetting(): void
    {
        $this->assertSame(
            ModelHandlerInterface::MAX_TOKEN_LENGTH,
            $this->getModelSettings->getMaxTokens('model3')
        );
    }
}
