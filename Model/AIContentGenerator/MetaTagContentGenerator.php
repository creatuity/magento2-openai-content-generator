<?php

declare(strict_types=1);

namespace Creatuity\AIContentOpenAI\Model\AIContentGenerator;

use Creatuity\AIContent\Api\AITypedContentGeneratorInterface;
use Creatuity\AIContent\Api\Data\AIResponseInterface;
use Creatuity\AIContent\Api\Data\SpecificationInterface;
use Creatuity\AIContent\Enum\AiContentTypeEnum;

class MetaTagContentGenerator extends DefaultTypedContentGenerator implements AITypedContentGeneratorInterface
{
    public function execute(SpecificationInterface $specification): AIResponseInterface
    {
        $response = parent::execute($specification);
        $choices = array_map(function (string $choice) use ($specification) {
            $replacement = match($specification->getContentType()) {
                AiContentTypeEnum::META_KEYWORDS_TYPE => ', ',
                default => '. '
            };

            return str_replace("\n", $replacement, strip_tags($choice));
        }, $response->getChoices());
        $response->setChoices($choices);

        return $response;
    }
}