<?php

/*
 * Copyright (c) 2026 Andrea Peverelli
 * https://github.com/andreapeverelli/phx-core.git
 *
 * SPDX-License-Identifier: GPL-3.0-only
 */

/**
 * @file ColorRole.php
 * @brief Material You color roles.
 */

declare(strict_types=1);

namespace AndreaPeverelli\PhxCore\Palette;

/**
 * @phpstan-type TonalPalettes array<
 *		value-of<Theme>,
 *		array<
 *			value-of<Contrast>,
 *			Tone
 *		>
 * >
 */

enum ColorRole: string
{
    // standard
    case ROLE = "role";
    case ON_ROLE = "on-role";
    case ROLE_CONTAINER = "role-container";
    case ON_ROLE_CONTAINER = "on-role-container";
    case ROLE_FIXED = "role-fixed";
    case ON_ROLE_FIXED = "on-role-fixed";
    case ROLE_FIXED_DIM = "role-fixed-dim";
    case ON_ROLE_FIXED_VARIANT = "on-role-fixed-variant";

    // special
    case INVERSE_PRIMARY = "inverse-primary";
    case SURFACE = "surface";
    case ON_SURFACE = "on-surface";
    case SURFACE_VARIANT = "surface-variant";
    case ON_SURFACE_VARIANT = "on-surface-variant";
    case SURFACE_CONTAINER_HIGEST = "surface-container-highest";
    case SURFACE_CONTAINER_HIGH = "surface-container-high";
    case SURFACE_CONTAINER = "surface-container";
    case SURFACE_CONTAINER_LOW = "surface-container-low";
    case SURFACE_CONTAINER_LOWEST = "surface-container-lowest";
    case INVERSE_SURFACE = "inverse-surface";
    case INVERSE_ON_SURFACE = "inverse-on-surface";
    case SURFACE_TINT = "surface-tint";
    case SURFACE_TINT_COLOR = "surface-tint-color";
    case OUTLINE = "outline";
    case OUTLINE_VARIANT = "outline_variant";
    case BACKGROUND = "background";
    case ON_BACKGROUND = "on-background";
    case SURFACE_BRIGHT = "surface-bright";
    case SURFACE_DIM = "surface-dim";
    case SCRIM = "scrim";
    case SHADOW = "shadow";


    /**
     * Get tones palette.
     *
     * @return TonalPalettes
     */
    final public function getTonesPalette(): array
    {
        return match ($this) {
            self::ROLE => [
                Theme::LIGHT->value => [
                    Contrast::DEFAULT->value => Tone::T40,
                    Contrast::HIGH->value => Tone::T20,
                ],
                Theme::DARK->value => [
                    Contrast::DEFAULT->value => Tone::T80,
                    Contrast::HIGH->value => Tone::T95,
                ],
            ],
            self::ON_ROLE => [
                Theme::LIGHT->value => [
                    Contrast::DEFAULT->value => Tone::T100,
                    Contrast::HIGH->value => Tone::T100,
                ],
                Theme::DARK->value => [
                    Contrast::DEFAULT->value => Tone::T20,
                    Contrast::HIGH->value => Tone::T0,
                ],
            ],
            self::ROLE_CONTAINER => [
                Theme::LIGHT->value => [
                    Contrast::DEFAULT->value => Tone::T90,
                    Contrast::HIGH->value => Tone::T30,
                ],
                Theme::DARK->value => [
                    Contrast::DEFAULT->value => Tone::T30,
                    Contrast::HIGH->value => Tone::T80,
                ],
            ],
            self::ON_ROLE_CONTAINER => [
                Theme::LIGHT->value => [
                    Contrast::DEFAULT->value => Tone::T30,
                    Contrast::HIGH->value => Tone::T100,
                ],
                Theme::DARK->value => [
                    Contrast::DEFAULT->value => Tone::T90,
                    Contrast::HIGH->value => Tone::T0,
                ],
            ],
            self::ROLE_FIXED => [
                Theme::LIGHT->value => [
                    Contrast::DEFAULT->value => Tone::T90,
                    Contrast::HIGH->value => Tone::T30,
                ],
                Theme::DARK->value => [
                    Contrast::DEFAULT->value => Tone::T90,
                    Contrast::HIGH->value => Tone::T90,
                ],
            ],
            self::ON_ROLE_FIXED => [
                Theme::LIGHT->value => [
                    Contrast::DEFAULT->value => Tone::T10,
                    Contrast::HIGH->value => Tone::T100,
                ],
                Theme::DARK->value => [
                    Contrast::DEFAULT->value => Tone::T10,
                    Contrast::HIGH->value => Tone::T0,
                ],
            ],
            self::ROLE_FIXED_DIM => [
                Theme::LIGHT->value => [
                    Contrast::DEFAULT->value => Tone::T80,
                    Contrast::HIGH->value => Tone::T20,
                ],
                Theme::DARK->value => [
                    Contrast::DEFAULT->value => Tone::T80,
                    Contrast::HIGH->value => Tone::T80,
                ],
            ],
            self::ON_ROLE_FIXED_VARIANT => [
                Theme::LIGHT->value => [
                    Contrast::DEFAULT->value => Tone::T30,
                    Contrast::HIGH->value => Tone::T100,
                ],
                Theme::DARK->value => [
                    Contrast::DEFAULT->value => Tone::T30,
                    Contrast::HIGH->value => Tone::T0,
                ],
            ],

            self::INVERSE_PRIMARY => [
                Theme::LIGHT->value => [
                    Contrast::DEFAULT->value => Tone::T80,
                    Contrast::HIGH->value => Tone::T80,
                ],
                Theme::DARK->value => [
                    Contrast::DEFAULT->value => Tone::T40,
                    Contrast::HIGH->value => Tone::T20,
                ],
            ],
            self::SURFACE => [
                Theme::LIGHT->value => [
                    Contrast::DEFAULT->value => Tone::T98,
                    Contrast::HIGH->value => Tone::T98,
                ],
                Theme::DARK->value => [
                    Contrast::DEFAULT->value => Tone::T6,
                    Contrast::HIGH->value => Tone::T6,
                ],
            ],
            self::ON_SURFACE => [
                Theme::LIGHT->value => [
                    Contrast::DEFAULT->value => Tone::T10,
                    Contrast::HIGH->value => Tone::T0,
                ],
                Theme::DARK->value => [
                    Contrast::DEFAULT->value => Tone::T90,
                    Contrast::HIGH->value => Tone::T100,
                ],
            ],
            self::SURFACE_VARIANT => [
                Theme::LIGHT->value => [
                    Contrast::DEFAULT->value => Tone::T90,
                    Contrast::HIGH->value => Tone::T90,
                ],
                Theme::DARK->value => [
                    Contrast::DEFAULT->value => Tone::T30,
                    Contrast::HIGH->value => Tone::T30,
                ],
            ],
            self::ON_SURFACE_VARIANT => [
                Theme::LIGHT->value => [
                    Contrast::DEFAULT->value => Tone::T30,
                    Contrast::HIGH->value => Tone::T0,
                ],
                Theme::DARK->value => [
                    Contrast::DEFAULT->value => Tone::T80,
                    Contrast::HIGH->value => Tone::T100,
                ],
            ],
            self::SURFACE_CONTAINER_HIGEST => [
                Theme::LIGHT->value => [
                    Contrast::DEFAULT->value => Tone::T90,
                    Contrast::HIGH->value => Tone::T90,
                ],
                Theme::DARK->value => [
                    Contrast::DEFAULT->value => Tone::T22,
                    Contrast::HIGH->value => Tone::T22,
                ],
            ],
            self::SURFACE_CONTAINER_HIGH => [
                Theme::LIGHT->value => [
                    Contrast::DEFAULT->value => Tone::T92,
                    Contrast::HIGH->value => Tone::T92,
                ],
                Theme::DARK->value => [
                    Contrast::DEFAULT->value => Tone::T17,
                    Contrast::HIGH->value => Tone::T17,
                ],
            ],
            self::SURFACE_CONTAINER => [
                Theme::LIGHT->value => [
                    Contrast::DEFAULT->value => Tone::T94,
                    Contrast::HIGH->value => Tone::T94,
                ],
                Theme::DARK->value => [
                    Contrast::DEFAULT->value => Tone::T12,
                    Contrast::HIGH->value => Tone::T12,
                ],
            ],
            self::SURFACE_CONTAINER_LOW => [
                Theme::LIGHT->value => [
                    Contrast::DEFAULT->value => Tone::T96,
                    Contrast::HIGH->value => Tone::T96,
                ],
                Theme::DARK->value => [
                    Contrast::DEFAULT->value => Tone::T10,
                    Contrast::HIGH->value => Tone::T10,
                ],
            ],
            self::SURFACE_CONTAINER_LOWEST => [
                Theme::LIGHT->value => [
                    Contrast::DEFAULT->value => Tone::T100,
                    Contrast::HIGH->value => Tone::T100,
                ],
                Theme::DARK->value => [
                    Contrast::DEFAULT->value => Tone::T4,
                    Contrast::HIGH->value => Tone::T4,
                ],
            ],
            self::INVERSE_SURFACE => [
                Theme::LIGHT->value => [
                    Contrast::DEFAULT->value => Tone::T20,
                    Contrast::HIGH->value => Tone::T20,
                ],
                Theme::DARK->value => [
                    Contrast::DEFAULT->value => Tone::T90,
                    Contrast::HIGH->value => Tone::T90,
                ],
            ],
            self::INVERSE_ON_SURFACE => [
                Theme::LIGHT->value => [
                    Contrast::DEFAULT->value => Tone::T95,
                    Contrast::HIGH->value => Tone::T100,
                ],
                Theme::DARK->value => [
                    Contrast::DEFAULT->value => Tone::T20,
                    Contrast::HIGH->value => Tone::T0,
                ],
            ],
            self::SURFACE_TINT => [
                Theme::LIGHT->value => [
                    Contrast::DEFAULT->value => Tone::T40,
                    Contrast::HIGH->value => Tone::T20,
                ],
                Theme::DARK->value => [
                    Contrast::DEFAULT->value => Tone::T80,
                    Contrast::HIGH->value => Tone::T95,
                ],
            ],
            self::SURFACE_TINT_COLOR => [
                Theme::LIGHT->value => [
                    Contrast::DEFAULT->value => Tone::T40,
                    Contrast::HIGH->value => Tone::T20,
                ],
                Theme::DARK->value => [
                    Contrast::DEFAULT->value => Tone::T80,
                    Contrast::HIGH->value => Tone::T95,
                ],
            ],
            self::OUTLINE => [
                Theme::LIGHT->value => [
                    Contrast::DEFAULT->value => Tone::T50,
                    Contrast::HIGH->value => Tone::T20,
                ],
                Theme::DARK->value => [
                    Contrast::DEFAULT->value => Tone::T60,
                    Contrast::HIGH->value => Tone::T95,
                ],
            ],
            self::OUTLINE_VARIANT => [
                Theme::LIGHT->value => [
                    Contrast::DEFAULT->value => Tone::T80,
                    Contrast::HIGH->value => Tone::T30,
                ],
                Theme::DARK->value => [
                    Contrast::DEFAULT->value => Tone::T30,
                    Contrast::HIGH->value => Tone::T80,
                ],
            ],
            self::BACKGROUND => [
                Theme::LIGHT->value => [
                    Contrast::DEFAULT->value => Tone::T98,
                    Contrast::HIGH->value => Tone::T98,
                ],
                Theme::DARK->value => [
                    Contrast::DEFAULT->value => Tone::T6,
                    Contrast::HIGH->value => Tone::T6,
                ],
            ],
            self::ON_BACKGROUND => [
                Theme::LIGHT->value => [
                    Contrast::DEFAULT->value => Tone::T10,
                    Contrast::HIGH->value => Tone::T0,
                ],
                Theme::DARK->value => [
                    Contrast::DEFAULT->value => Tone::T90,
                    Contrast::HIGH->value => Tone::T100,
                ],
            ],
            self::SURFACE_BRIGHT => [
                Theme::LIGHT->value => [
                    Contrast::DEFAULT->value => Tone::T98,
                    Contrast::HIGH->value => Tone::T98,
                ],
                Theme::DARK->value => [
                    Contrast::DEFAULT->value => Tone::T24,
                    Contrast::HIGH->value => Tone::T24,
                ],
            ],
            self::SURFACE_DIM => [
                Theme::LIGHT->value => [
                    Contrast::DEFAULT->value => Tone::T87,
                    Contrast::HIGH->value => Tone::T87,
                ],
                Theme::DARK->value => [
                    Contrast::DEFAULT->value => Tone::T6,
                    Contrast::HIGH->value => Tone::T6,
                ],
            ],
            self::SCRIM => [
                Theme::LIGHT->value => [
                    Contrast::DEFAULT->value => Tone::T0,
                    Contrast::HIGH->value => Tone::T0,
                ],
                Theme::DARK->value => [
                    Contrast::DEFAULT->value => Tone::T0,
                    Contrast::HIGH->value => Tone::T0,
                ],
            ],
            self::SHADOW => [
                Theme::LIGHT->value => [
                    Contrast::DEFAULT->value => Tone::T0,
                    Contrast::HIGH->value => Tone::T0,
                ],
                Theme::DARK->value => [
                    Contrast::DEFAULT->value => Tone::T0,
                    Contrast::HIGH->value => Tone::T0,
                ],
            ],
        };
    }
}
