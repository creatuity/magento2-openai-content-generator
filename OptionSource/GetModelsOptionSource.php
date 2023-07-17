<?php

declare(strict_types=1);

namespace Creatuity\AIContentOpenAI\OptionSource;

use Creatuity\AIContentOpenAI\Model\GetModelSettings;
use Magento\Framework\Data\OptionSourceInterface;

class GetModelsOptionSource implements OptionSourceInterface
{
    public function __construct(
        private readonly GetModelSettings $modelSettings
    ) {
    }

    /**
     * @return string[][]
     */
    public function toOptionArray(): array
    {
        return array_map(function ($model) {
            return ['label' => $model, 'value' => $model];
        }, $this->modelSettings->getModelNames());
    }
}