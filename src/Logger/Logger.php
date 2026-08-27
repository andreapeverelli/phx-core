<?php

/*
 * Copyright (c) 2026 Andrea Peverelli
 * https://github.com/andreapeverelli/phx-core.git
 *
 * SPDX-License-Identifier: GPL-3.0-only
 */

/**
 * @file Logger.php
 * @brief Logger utilities.
 */

declare(strict_types=1);

namespace AndreaPeverelli\PhxCore;

use Monolog\Logger as MonologLogger;
use Monolog\Handler\StreamHandler;

final class Logger
{
    final public static function create(): MonologLogger
    {
        return (new MonologLogger("phx_" . uniqid()))->pushHandler(new StreamHandler("php://stderr"));
    }
}
