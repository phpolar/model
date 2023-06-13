<?php

declare(strict_types=1);

namespace Phpolar;

use Generator;
use PHPUnit\Framework\Attributes\CoversNothing;
use PHPUnit\Framework\Attributes\DataProvider;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;

use const \Phpolar\Tests\PROJECT_SIZE_THRESHOLD;

#[CoversNothing]
final class ProjectSizeTest extends TestCase
{
    public static function thresholds(): Generator
    {
        yield [(int) PROJECT_SIZE_THRESHOLD];
    }

    #[Test]
    #[TestDox("Source code total size shall be below \$threshold bytes")]
    #[DataProvider("thresholds")]
    public function shallBeBelowThreshold(int $threshold)
    {
        $totalSize = mb_strlen(
            implode(
                preg_replace(
                    [
                        // strip comments
                        "/\/\*\*(.*?)\//s",
                        "/^(.*?)\/\/(.*?)$/s",
                    ],
                    "",
                    array_map(
                        file_get_contents(...),
                        glob(getcwd() . SRC_GLOB, GLOB_BRACE),
                    ),
                ),
            )
        );
        $this->assertGreaterThan(0, $totalSize);
        $this->assertLessThanOrEqual($threshold, $totalSize);
    }
}
