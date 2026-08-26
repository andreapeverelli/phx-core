<?php

/*
 * Copyright (c) 2026 Andrea Peverelli
 * https://github.com/andreapeverelli/phx-core.git
 *
 * SPDX-License-Identifier: GPL-3.0-only
 */

/**
 * @file App.php
 * @brief App class containing all application utilities like PHX settings and the logger instance
 */

declare(strict_types=1);

namespace AndreaPeverelli\PhxCore;

use Psr\Log\LoggerInterface;

/** @phpstan-import-type Settings from \AndreaPeverelli\PhxCore\Settings\Setting */
final readonly class App
{
    public function __construct(
        /** @var Settings */
        public private(set) array $settings,
        public private(set) LoggerInterface $logger,
    ) {}
}
