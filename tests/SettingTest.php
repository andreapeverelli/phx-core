<?php

/*
 * Copyright (c) 2026 Andrea Peverelli
 * https://github.com/andreapeverelli/phx-core.git
 *
 * SPDX-License-Identifier: GPL-3.0-only
 */

/**
 * @file SettingTest.php
 * @brief Setting handler unit tests.
 */

declare(strict_types=1);

namespace Tests;

use AndreaPeverelli\PhxCore\Setting;
use AndreaPeverelli\PhxCore\SettingFile;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;

final class SettingTest extends TestCase
{
    /*
     * TESTS:
     *  - constructor
     */

    #[Test]
    #[TestDox("Constructor")]
    public function consctructSetting(): void
    {

        /**************************************************
         * SETUP                                          *
         **************************************************/

        $setting = new Setting(phx_cli_config_path: __DIR__ . "/../settings/default.cli.phx.config.json");

        /**************************************************
         * TESTS                                          *
         **************************************************/

        $this->assertNotEmpty($setting->settings[SettingFile::PALETTE->value]);
        $this->assertNotEmpty($setting->settings[SettingFile::TYPESCALE->value]);
        $this->assertNotEmpty($setting->settings[SettingFile::PHX_CONFIG->value]);
        $this->assertNotEmpty($setting->settings[SettingFile::PHX_CLI_CONFIG->value]);
    }
}
