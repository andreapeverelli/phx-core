<?php

/*
 * Copyright (c) 2026 Andrea Peverelli
 * https://github.com/andreapeverelli/phx-core.git
 *
 * SPDX-License-Identifier: GPL-3.0-only
 */

/**
 * @file Contrast.php
 * @brief Available contrasts.
 */

declare(strict_types=1);

namespace AndreaPeverelli\PhxCore\Palette;

enum Contrast: string
{
    case DEFAULT = "default-contrast";
    case HIGH = "high-contrast";
}
