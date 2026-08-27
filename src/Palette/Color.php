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

namespace AndreaPeverelli\PhxCore;

use AndreaPeverelli\PhxCore\Palette\Base;
use AndreaPeverelli\PhxCore\Palette\Role;

final class Color
{
    public function __construct(
        public private(set) readonly Role $role,
        public private(set) Base $base = Base::NEUTRAL,
    ) {
        if (
            $role === Role::SURFACE_VARIANT
            || $role === Role::ON_SURFACE_VARIANT
            || $role === Role::OUTLINE
            || $role === Role::OUTLINE_VARIANT
        ) {
            $this->base = Base::VARIANT;
        } elseif (
            $role !== Role::ROLE
            && $role !== Role::ON_ROLE
            && $role !== Role::ROLE_CONTAINER
            && $role !== Role::ON_ROLE_CONTAINER
            && $role !== Role::ROLE_FIXED
            && $role !== Role::ON_ROLE_FIXED
            && $role !== Role::ROLE_FIXED_DIM
            && $role !== Role::ON_ROLE_FIXED_VARIANT
        ) {
            $this->base = Base::NEUTRAL;
        }
    }

    public function __toString(): string
    {
        return "Base Color: {$this->base->value} - Role: {$this->role->value}";
    }
}
