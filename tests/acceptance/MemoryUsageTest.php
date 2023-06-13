<?php

declare(strict_types=1);

namespace Phpolar;

use Generator;
use Phpolar\Model\AbstractModel;
use Phpolar\Model\Column;
use Phpolar\Model\Hidden;
use Phpolar\Model\Label;
use Phpolar\Model\PrimaryKey;
use Phpolar\Model\Size;
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;

use const \Phpolar\Tests\PROJECT_MEMORY_USAGE_THRESHOLD;

#[CoversNothing]
final class MemoryUsageTest extends TestCase
{
    public static function thresholds(): Generator
    {
        yield [(int) PROJECT_MEMORY_USAGE_THRESHOLD];
    }

    #[Test]
    #[TestDox("Memory usage for a get request shall be below \$threshold bytes")]
    #[DataProvider("thresholds")]
    public function shallBeBelowThreshold1(int $threshold)
    {
        $totalUsed = -memory_get_usage();
        $model = new class () extends AbstractModel {
            #[Size(5)]
            #[Column("what")]
            public int $num;

            #[Hidden]
            public string $hideMe;

            #[Label("Consider yourself labeled")]
            public string $name;

            #[PrimaryKey]
            public string $id;
        };
        $model->getColumnName("num");
        $model->getLabel("name");
        $model->getPrimaryKey();
        $model->getSize("num");
        $totalUsed += memory_get_usage();
        $this->assertGreaterThan(0, $totalUsed);
        $this->assertLessThanOrEqual($threshold, $totalUsed);
    }
}
