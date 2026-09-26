<?php

/*
 * Copyright (c) 2026 Andrea Peverelli
 * https://github.com/andreapeverelli/phx-core.git
 *
 * SPDX-License-Identifier: GPL-3.0-only
 */

/**
 * @file SettingFile.php
 * @brief Available PHX settings.
 */

declare(strict_types=1);

namespace AndreaPeverelli\PhxCore;

enum SettingFile: string
{
    case PALETTE = "palette";
    case TYPESCALE = "typescale";
    case PHX_CONFIG = "phx-config";
    case PHX_CLI_CONFIG = "phx-cli-config";
}
