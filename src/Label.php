<?php

declare(strict_types=1);

namespace Phpolar\Model;

use Attribute;

/**
 * Provides support for automatic formatting
 * and configuring the text for form field labels.
 */
#[Attribute(Attribute::TARGET_PROPERTY)]
final class Label extends AbstractPropertyNameExtractor
{
    public function __construct(private string|LabelFormatConfig $arg = LabelFormatConfig::T_Default)
    {
    }

    /**
     * Returns the formatted label.
     *
     * @api
     */
    public function getLabel(): string
    {
        return match ($this->arg) {
            LabelFormatConfig::T_Default => (new DefaultLabelFormat($this->propName))->getLabel(),
            default => (string) $this->arg
        };
    }
}
