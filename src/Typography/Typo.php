<?php

/*
 * Copyright (c) 2026 Andrea Peverelli
 * https://github.com/andreapeverelli/phx-core.git
 *
 * SPDX-License-Identifier: GPL-3.0-only
 */

/**
 * @file Typo.php
 * @brief PHX typography class.
 */

declare(strict_types=1);

namespace AndreaPeverelli\PhxCore;

use AndreaPeverelli\PhxCore\Typography\Role;
use AndreaPeverelli\PhxCore\Typography\SubRole;
use AndreaPeverelli\PhxCore\Typography\Emphasized;

final readonly class Typo
{
    public function __construct(
        public private(set) Role $role,
        public private(set) SubRole $sub_role,
        public private(set) Emphasized $emphasized = Emphasized::REGULAR,
    ) {}

    public function __toString(): string
    {
        return "Role: {$this->role->value} - SubRole: {$this->sub_role->value} - Emphasized: {$this->emphasized->value}";
    }
}
