<?php

/*
 * Copyright (c) 2026 Andrea Peverelli
 * https://github.com/andreapeverelli/phx-core.git
 *
 * SPDX-License-Identifier: GPL-3.0-only
 */

/**
 * @file Settings.php
 * @brief Available PHX settings.
 */

declare(strict_types=1);

namespace AndreaPeverelli\PhxCore;

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
enum Setting: string
{
    case PALETTE = "palette";
    case TYPESCALE = "typescale";

    /**
     * @return (
     *      $this is self::PALETTE ?
     *          PaletteSettings :
     *          TypescaleSettings
     * )
     */
    final public function load(?string $path = null): array
    {
        if ($this === self::PALETTE) {
            $_path = $path ?? __DIR__ . "/../../settings/default.palette.json";

            /** @var PaletteSettings */
            $settings = json_decode((string) file_get_contents($_path), true);
            return $settings;
        }

        // if ($this === self::TYPESCALE) {
        $_path = $path ?? __DIR__ . "/../../settings/default.typescale.json";

        /** @var TypescaleSettings */
        $settings = json_decode((string) file_get_contents($_path), true);
        return $settings;
        // }
    }


    /** @return Settings */
    final public static function loadAll(): array
    {
        return [
            self::PALETTE->value => self::PALETTE->load(),
            self::TYPESCALE->value => self::TYPESCALE->load(),
        ];
    }
}
