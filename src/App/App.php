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

/**
 * @phpstan-type PaletteSettings array<
 *		value-of<\AndreaPeverelli\PhxCore\Palette\BaseColor>,
 *		array<
 *			value-of<\AndreaPeverelli\PhxCore\Palette\Tone>,
 *			array{
 *				srgb: string,
 *				display-p3: array{r: float, g: float, b: float},
 *				rec2020: array{r: float, g: float, b: float},
 *			}
 *		>
 * >
 * @phpstan-type TypescaleSettings array<
 *		value-of<\AndreaPeverelli\PhxCore\Typography\TypoRole>,
 *		array<
 *			value-of<\AndreaPeverelli\PhxCore\Typography\TypoSubRole>,
 *			array{
 *				font-size: float,
 *				font-weight: array<
 *					value-of<\AndreaPeverelli\PhxCore\Typography\Emphasized>,
 *					int
 *				>,
 *				line-height: float,
 *				letter-spacing: float,
 *			}
 *		>
 * >
 * @phpstan-type Settings array{
 *		palette: PaletteSettings,
 *		typescale: TypescaleSettings,
 * }
 */
final readonly class App
{
    public function __construct(
        /** @var Settings */
        public private(set) array $settings,
        public private(set) LoggerInterface $logger,
    ) {}
}
