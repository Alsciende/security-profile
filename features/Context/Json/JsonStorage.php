<?php

declare(strict_types=1);

namespace App\Features\Context\Json;

class JsonStorage
{
    /**
     * @var mixed
     */
    private $rawContent;

    public function writeRawContent($rawContent)
    {
        $this->rawContent = $rawContent;
    }

    public function readJson()
    {
        if (null === $this->rawContent) {
            throw new \LogicException('No content defined. You should use JsonStorage::writeRawContent method to inject content you want to analyze');
        }

        return new Json($this->rawContent);
    }
}
