<?php

/*
 * Copyright (c) 2026 Andrea Peverelli
 * https://github.com/andreapeverelli/phx-core.git
 *
 * SPDX-License-Identifier: GPL-3.0-only
 */

/**
 * @file Gamut.php
 * @brief Available gamuts.
 */

namespace AndreaPeverelli\PhxCore\Palette;

enum Gamut: string
{
    case SRGB = "srgb";
    case DISPLAY_P3 = "display-p3";
    case REC2020 = "rec2020";
}
