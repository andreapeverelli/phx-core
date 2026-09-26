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

use AndreaPeverelli\PhxCore\SettingFile;
use Psr\Log\LoggerInterface;

/**
 * @phpstan-import-type Settings from \AndreaPeverelli\PhxCore\Setting
 * @phpstan-import-type Font from \AndreaPeverelli\PhxCore\Component
 * @phpstan-import-type Settings from \AndreaPeverelli\PhxCore\Setting
 */
final readonly class App
{
    public function __construct(
        /** @var Settings */
        public private(set) array $settings,
        public private(set) LoggerInterface $logger,
    ) {}

    /** @param \AndreaPeverelli\PhxCore\Component<\AndreaPeverelli\PhxCore\Props>[] $components */
    public function render(
        string $title,
        string $description,
        string $body,
        array $components,
    ): string {
        $css = [];
        $js_before = [];
        $js_after = [];
        $fonts = [];

        foreach ($components as $component) {
            array_push($css, ...$component->css);
            array_push($js_before, ...$component->js_before);
            array_push($js_after, ...$component->js_after);
            array_push($fonts, ...$component->fonts);
        }

        $css_string = implode("\n\n", array_unique($css));
        $js_before_string = implode("\n\n", array_map([App::class, "renderJs"], array_unique($js_before)));
        $js_after_string = implode("\n\n", array_map([App::class, "renderJs"], array_unique($js_after)));
        $fonts_string = implode("\n\n", array_map([App::class, "renderFont"], array_unique($fonts, SORT_REGULAR)));

        $palette = $this->settings[SettingFile::PALETTE->value];
        $app_name = $this->settings[SettingFile::PHX_CONFIG->value]["app-name"];
        $language = $this->settings[SettingFile::PHX_CONFIG->value]["languages"];
        $homepage = $this->settings[SettingFile::PHX_CONFIG->value]["homepage"];
        $url = $this->settings[SettingFile::PHX_CONFIG->value]["homepage"];

        $icons_uri = $this->settings[SettingFile::PHX_CLI_CONFIG->value]["icons-uri"];

        $html_lang = explode(",", $language)[0];

        return <<<HTML
        <!doctype html>
        <html lang="$html_lang">
        <head>
            <meta charset="utf-8">

            <meta
                name="viewport"
                content="width=device-width, initial-scale=1, viewport-fit=cover"
            >

            <title>$title</title>
            <meta name="description" content="$description">
            <meta name="author" content="{$this->settings[SettingFile::PHX_CONFIG->value]["name-surname"]}">

            <meta name="robots" content="index, follow, max-image-preview:large">
            <meta name="googlebot" content="index, follow">

            <link rel="canonical" href="$homepage">

            <meta name="theme-color" content="{$palette["primary"][40]["srgb"]}">
            <meta name="color-scheme" content="light dark">

            <link rel="manifest" href="/site.webmanifest">

            <link
                rel="icon"
                href="$icons_uri/favicon.ico"
                sizes="any"
            >
            <link
                rel="icon"
                type="image/svg+xml"
                href="$icons_uri/favicon.svg"
            >

            <link
                rel="icon"
                type="image/png"
                sizes="32x32"
                href="$icons_uri/favicon-32x32.png"
            >
            <link
                rel="icon"
                type="image/png"
                sizes="16x16"
                href="$icons_uri/favicon-16x16.png"
            >

            <link
                rel="apple-touch-icon"
                sizes="180x180"
                href="$icons_uri/apple-touch-icon.png"
            >

            <link
                rel="icon"
                type="image/png"
                sizes="192x192"
                href="$icons_uri/icon-192.png"
            >
            <link
                rel="icon"
                type="image/png"
                sizes="512x512"
                href="$icons_uri/icon-512.png"
            >

            <meta
                name="msapplication-TileColor"
                content="{$palette["primary"][40]["srgb"]}"
            >
            <meta
                name="msapplication-TileImage"
                content="$icons_uri/mstile-144x144.png"
            >

            <meta property="og:type" content="website">
            <meta property="og:url" content="$url">
            <meta property="og:title" content="$title">
            <meta property="og:description" content="$description">
            <meta
                property="og:image"
                content="$homepage$icons_uri/og-image.png"
            >
            <meta property="og:image:width" content="1200">
            <meta property="og:image:height" content="630">
            <meta property="og:locale" content="$language">

            <meta name="twitter:card" content="summary_large_image">
            <meta name="twitter:url" content="$url">
            <meta name="twitter:title" content="$title">
            <meta name="twitter:description" content="$description">
            <meta
                name="twitter:image"
                content="$homepage$icons_uri/og-image.png"
            >

            <script type="application/ld+json">
                {
                    "@context": "https://schema.org",
                    "@type": "WebSite",
                    "name": "$app_name",
                    "url": "$homepage"
                }
            </script>
        </head>
        <body>
            $body
        </body>
        </html>
        HTML;
    }

    private static function renderJs(string $js): string
    {
        return <<<HTML
        <script type="text/javascript">
            $js
        </script>
        HTML;
    }

    /** @param Font $font */
    private static function renderFont(array $font): string
    {
        $css = <<<CSS
        @font-face {
            font-family: {$font["font-family"]};
            src:
                url("/font/{$font["font-family"]}/{$font["font-family"]}.woff2") format("woff2"),
                url("/font/{$font["font-family"]}/{$font["font-family"]}.woff") format("woff");
        }
        CSS;

        if ($font["italic"] === true) {
            $css = <<<CSS
            
            @font-face {
                font-family: {$font["font-family"]};
                font-style: italic;
                src:
                    url("/font/{$font["font-family"]}/{$font["font-family"]}-italic.woff2") format("woff2"),
                    url("/font/{$font["font-family"]}/{$font["font-family"]}-italic.woff") format("woff");
            }
            CSS;
        }

        return $css;
    }
}
