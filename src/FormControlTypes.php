<?php

declare(strict_types=1);

namespace Phpolar\Model;

/**
 * Contains most form control types.
 */
enum FormControlTypes
{
    case Select;
    case Input;
    case Invalid;
}
