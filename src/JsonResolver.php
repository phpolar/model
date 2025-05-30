<?php

namespace Phpolar\Model;

use Psr\Http\Message\ServerRequestInterface;

/**
 * Deserializes JSON from a request's body
 * to resolve the model.
 */
final class JsonResolver extends AbstractModelResolver
{
    public function __construct(
        ServerRequestInterface $request,
    ) {
        parent::__construct(
            json_decode(
                $request->getBody()->getContents()
            )
        );
    }
}
