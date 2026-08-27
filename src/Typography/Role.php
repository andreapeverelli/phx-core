<?php

/*
 * Copyright (c) 2026 Andrea Peverelli
 * https://github.com/andreapeverelli/phx-core.git
 *
 * SPDX-License-Identifier: GPL-3.0-only
 */

/**
 * @file TypoRole.php
 * @brief Material You typography roles with font-face.
 */

declare(strict_types=1);

namespace AndreaPeverelli\PhxCore\Typography;

enum Role: string
{
    case DISPLAY = "display";
    case HEADLINE = "headline";
    case TITLE = "title";

    case BODY = "body";
    case LABEL = "label";

    final public function getFontFamily(): string
    {
        return match ($this) {
            self::DISPLAY,
            self::HEADLINE,
            self::TITLE => "phx-heading",

            self::BODY,
            self::LABEL => "phx-copy",
        };
    }
}
