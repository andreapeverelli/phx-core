<?php

/*
 * Copyright (c) 2026 Andrea Peverelli
 * https://github.com/andreapeverelli/phx-core.git
 *
 * SPDX-License-Identifier: GPL-3.0-only
 */

/**
 * @file BaseColor.php
 * @brief Available base color palettes.
 */

namespace AndreaPeverelli\PhxCore\Palette;

enum BaseColor: string
{
    // standard
    case PRIMARY = "primary";
    case SECONDARY = "secondary";
    case TERTIARY = "tertiary";
    case ERROR = "error";
    case WARNING = "warning";
    case INFO = "info";

    // rainbow
    case RED = "red";
    case ORANGE = "orange";
    case YELLOW = "yellow";
    case GREEN = "green";
    case CYAN = "cyan";
    case BLUE = "blue";
    case INDIGO = "indigo";
    case VIOLET = "violet";

    // other common colors
    case GREY = "grey";
    case BROWN = "brown";
    case PINK = "pink";
    case MAGENTA = "magenta";
    case FUCHSIA = "fuchsia";
    case PURPLE = "purple";
    case LIGHTBLUE = "lightblue";
}
