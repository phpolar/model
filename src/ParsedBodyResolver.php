<?php

declare(strict_types=1);

namespace Phpolar\Model;

/**
 * Uses the parsed body from a request
 * to resolve the model.
 */
final class ParsedBodyResolver extends AbstractModelResolver
{
    /**
     * @param array<string,string>|object|null $parsedRequestBody
     */
    public function __construct(
        array|object|null $parsedRequestBody,
    ) {
        parent::__construct($parsedRequestBody);
    }
}
