<?php

declare(strict_types=1);

namespace Phpolar\Model\Tests\Stubs;

use Phpolar\Model\EntityName;
use Phpolar\Model\EntityNameConfigurationTrait;

use const Phpolar\Model\Tests\ENTITY_NAME_TEST_CASE;

#[EntityName(ENTITY_NAME_TEST_CASE)]
final class EntityNameConfigured
{
    use EntityNameConfigurationTrait;
}
