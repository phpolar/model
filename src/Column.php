<?php

declare(strict_types=1);

namespace Phpolar\Model;

use Attribute;

/**
 * Provides support for configuring the text for column names in records.
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
final class Column extends AbstractPropertyNameExtractor
{
    public function __construct(private string|ColumnConfig $arg = ColumnConfig::T_Default)
    {
    }

    /**
     * Returns the configured column name.
     *
     * @api
     */
    public function getColumnName(): string
    {
        return match ($this->arg) {
            ColumnConfig::T_Default => (new DefaultColumnName($this->propName))->getColumnName(),
            default => (string) $this->arg
        };
    }
}
