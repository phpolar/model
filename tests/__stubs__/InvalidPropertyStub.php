<?php

declare(strict_types=1);

namespace Phpolar\Model\Tests\Stubs;

use Attribute;
use PhpContrib\Validator\MessageGetterInterface;
use PhpContrib\Validator\ValidatorInterface;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class InvalidPropertyStub implements ValidatorInterface, MessageGetterInterface
{
    public const EXPECTED_MESSAGE = "IS INVALID";

    public function isValid(): bool
    {
        return false;
    }

    public function getMessage(): string
    {
        return self::EXPECTED_MESSAGE;
    }
}
