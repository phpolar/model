<?php

declare(strict_types=1);

namespace Phpolar\Model\Tests\DataProviders;

use DateTimeImmutable;

class AutomaticDateValueTestData
{
    public static function testCases()
    {
        return [[new DateTimeImmutable()]];
    }
}
