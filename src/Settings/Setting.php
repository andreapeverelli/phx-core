<?php

/*
 * Copyright (c) 2026 Andrea Peverelli
 * https://github.com/andreapeverelli/phx-core.git
 *
 * SPDX-License-Identifier: GPL-3.0-only
 */

/**
 * @file Settings.php
 * @brief Available PHX settings.
 */

declare(strict_types=1);

namespace AndreaPeverelli\PhxCore\Settings;

enum Setting: string
{
    case PALETTE = "palette";
    case TYPESCALE = "typescale";
}
