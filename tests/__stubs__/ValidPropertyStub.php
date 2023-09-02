<?php

declare(strict_types=1);

namespace Phpolar\Model\Tests\Stubs;

use Attribute;
use PhpContrib\Validator\MessageGetterInterface;
use PhpContrib\Validator\ValidatorInterface;

#[Attribute(Attribute::TARGET_PROPERTY)]
final class ValidPropertyStub implements ValidatorInterface, MessageGetterInterface
{
    public const EXPECTED_MESSAGE = "IS VALID";

    public function isValid(): bool
    {
        return true;
    }

    public function getMessage(): string
    {
        return "";
    }
}
