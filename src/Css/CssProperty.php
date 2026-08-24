<?php

/*
 * Copyright (c) 2026 Andrea Peverelli
 * https://github.com/andreapeverelli/phx-core.git
 *
 * SPDX-License-Identifier: GPL-3.0-only
 */

/**
 * @file CssProperty.php
 * @brief CSS properties list.
 */

declare(strict_types=1);

namespace AndreaPeverelli\PhxCore\Css;

enum CssProperty: string
{
    case COLOR = "color";
    case BACKGROUND_COLOR = "background-color";
    case FILL = "fill";
}
