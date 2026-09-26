<?php

/*
 * Copyright (c) 2026 Andrea Peverelli
 * https://github.com/andreapeverelli/phx-core.git
 *
 * SPDX-License-Identifier: GPL-3.0-only
 */

/**
 * @file Settings.php
 * @brief Handle PHX settings loading and storing
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
 *
 * @phpstan-type PhxConfigSettings array{
 *      "app-name": string,
 *      "app-short-name": string,
 *      "vendor": string,
 *      "description": string,
 *      "version": string,
 *      "license": string,
 *      "domain": string,
 *      "homepage": string,
 *      "languages": string,
 *      "categories": string,
 *      "name-surname": string,
 *      "email": string,
 *      "personal-website": string,
 *      "x": string,
 *      "github": string,
 * }
 *
 * @phpstan-type PhxCliConfigSettings array{
 *      "phx-config-path": string,
 *      "palette-path": string,
 *      "typescale-path": string,
 *      "main-font-name": string,
 *      "support-font-name": string,
 *      "icons-uri": string,
 * }
 *
 * @phpstan-type Settings array{
 *		palette: PaletteSettings,
 *		typescale: TypescaleSettings,
 *		"phx-config": PhxConfigSettings,
 *		"phx-cli-config": PhxCliConfigSettings,
 * }
 */
final class Setting
{
    /** @var Settings */
    public array $settings;

    /**
     * @var array{
     *      palette: string,
     *      typescale: string,
     *      "phx-config": string,
     *      "phx-cli-config": string,
     * }
     */
    private array $file_path = [
        "palette" => "",
        "typescale" => "",
        "phx-config" => "",
        "phx-cli-config" => __DIR__ . "/../../settings/default.cli.phx.config.json",
    ];

    public function __construct(string $phx_cli_config_path = "")
    {
        if ($phx_cli_config_path !== "") {
            $this->file_path["phx-cli-config"] = $phx_cli_config_path;
        }

        $this->load();
    }

    public function load(): void
    {
        /** @var PhxCliConfigSettings */
        $phx_cli_config = json_decode((string) file_get_contents($this->file_path[SettingFile::PHX_CLI_CONFIG->value]), true);
        $this->settings[SettingFile::PHX_CLI_CONFIG->value] = $phx_cli_config;

        // Setting file paths
        $this->file_path[SettingFile::PALETTE->value]
            = $phx_cli_config["palette-path"]
            ?: __DIR__ . "/../../settings/default.palette.json";
        $this->file_path[SettingFile::TYPESCALE->value]
            = $phx_cli_config["typescale-path"]
            ?: __DIR__ . "/../../settings/default.typescale.json";
        $this->file_path[SettingFile::PHX_CONFIG->value]
            = $phx_cli_config["phx-config-path"]
            ?: __DIR__ . "/../../settings/default.phx.config.json";

        /** @var PaletteSettings */
        $palette = json_decode((string) file_get_contents($this->file_path[SettingFile::PALETTE->value]), true);
        $this->settings[SettingFile::PALETTE->value] = $palette;

        /** @var TypescaleSettings */
        $typescale = json_decode((string) file_get_contents($this->file_path[SettingFile::TYPESCALE->value]), true);
        $this->settings[SettingFile::TYPESCALE->value] = $typescale;

        /** @var PhxConfigSettings */
        $phx_config = json_decode((string) file_get_contents($this->file_path[SettingFile::PHX_CONFIG->value]), true);
        $this->settings[SettingFile::PHX_CONFIG->value] = $phx_config;
    }
}
