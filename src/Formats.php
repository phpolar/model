<?php

declare(strict_types=1);

namespace Phpolar\Model;

/**
 * Contains formattable strings.
 */
enum Formats: string
{
    case ErrorTemplates = "src/templates/%s.phtml";
    case ErrorText = "<h1>%s</h1>";
}
