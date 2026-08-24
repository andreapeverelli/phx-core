<?php

/*
 * Copyright (c) 2026 Andrea Peverelli
 * https://github.com/andreapeverelli/phx-core.git
 *
 * SPDX-License-Identifier: GPL-3.0-only
 */

/**
 * @file Color.php
 * @brief Color management implementation.
 */

declare(strict_types=1);

namespace AndreaPeverelli\PhxCore\Palette;

final readonly class Color
{
    public function __construct(
        public private(set) BaseColor $base,
        public private(set) ColorRole $role,
    ) {}

    public function __toString(): string
    {
        return "Base Color: {$this->base->value} - Role: {$this->role->value}";
    }
}
