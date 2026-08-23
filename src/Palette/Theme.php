<?php

/*
 * Copyright (c) 2026 Andrea Peverelli
 * https://github.com/andreapeverelli/phx-core.git
 *
 * SPDX-License-Identifier: GPL-3.0-only
 */

/**
 * @file Theme.php
 * @brief Available themes.
 */

namespace AndreaPeverelli\PhxCore\Palette;

enum Theme: string
{
    case LIGHT = "light";
    case DARK = "dark";
}
