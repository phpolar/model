<?php

declare(strict_types=1);

namespace Phpolar\Model;

use Phpolar\Model\Tests\Stubs\EntityNameConfigured;
use Phpolar\Model\Tests\Stubs\EntityNameNotConfigured;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\CoversTrait;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;
use ReflectionClass;

use const Phpolar\Model\Tests\ENTITY_NAME_TEST_CASE;

#[CoversClass(EntityName::class)]
#[CoversTrait(EntityNameConfigurationTrait::class)]
final class EntityNameConfigurationTraitTest extends TestCase
{
    #[TestDox("Shall return the configured entity name")]
    public function test1()
    {
        $entity = new EntityNameConfigured();
        $actual = $entity->getName();
        $this->assertSame(ENTITY_NAME_TEST_CASE, $actual);
    }

    #[TestDox("Shall return the configured entity name")]
    public function test2()
    {
        $entity = new EntityNameNotConfigured();
        $actual = $entity->getName();
        $this->assertSame(
            (new ReflectionClass(EntityNameNotConfigured::class))->getShortName(),
            $actual
        );
    }
}
