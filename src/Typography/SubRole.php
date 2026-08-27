<?php

/*
 * Copyright (c) 2026 Andrea Peverelli
 * https://github.com/andreapeverelli/phx-core.git
 *
 * SPDX-License-Identifier: GPL-3.0-only
 */

/**
 * @file TypoSubRole.php
 * @brief Material You typography subroles.
 */

declare(strict_types=1);

namespace AndreaPeverelli\PhxCore\Typography;

enum SubRole: string
{
    case LARGE = "large";
    case MEDIUM = "medium";
    case SMALL = "small";
}
