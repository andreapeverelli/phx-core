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

namespace AndreaPeverelli\PhxCore\Typography;

final class Typo
{
    public function __construct(
        public TypoRole $role,
        public TypoSubRole $sub_role,
        public Emphasized $emphasized = Emphasized::REGULAR,
    ) {}

    public function __toString(): string
    {
        return "Role: {$this->role->value} - SubRole: {$this->sub_role->value} - Emphasized: {$this->emphasized->value}";
    }
}
