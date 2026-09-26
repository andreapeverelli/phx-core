<?php

/*
 * Copyright (c) 2026 Andrea Peverelli
 * https://github.com/andreapeverelli/phx-core.git
 *
 * SPDX-License-Identifier: GPL-3.0-only
 */

/**
 * @file AppTest.php
 * @brief App unit tests.
 */

declare(strict_types=1);

namespace Tests;

use AndreaPeverelli\PhxCore\App;
use AndreaPeverelli\PhxCore\Component;
use AndreaPeverelli\PhxCore\Props;
use AndreaPeverelli\PhxCore\Setting;
use AndreaPeverelli\PhxCore\Logger;
use AndreaPeverelli\PhxCore\Typo;
use AndreaPeverelli\PhxCore\Typography\Role;
use AndreaPeverelli\PhxCore\Typography\SubRole;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use PHPUnit\Framework\TestCase;

final class AppTest extends TestCase
{
    /*
     * TESTS:
     *  - render
     */

    #[Test]
    #[TestDox("Render page")]
    final public function renderPage(): void
    {
        $app = new App(
            logger: Logger::create(),
            settings: new Setting()->settings,
        );

        $component = new AppTestComponent();
        $component->addComponentTypo(typo: new Typo(role: Role::BODY, sub_role: SubRole::LARGE));

        $render = $app->render(
            title: "Test",
            description: "Test render",
            body: "Test",
            components: [$component],
        );

        $this->assertSame(
            <<<HTML
            <!doctype html>
            <html lang="en">
            <head>
                <meta charset="utf-8">

                <meta
                    name="viewport"
                    content="width=device-width, initial-scale=1, viewport-fit=cover"
                >

                <title>Test</title>
                <meta name="description" content="Test render">
                <meta name="author" content="Andrea Peverelli">

                <meta name="robots" content="index, follow, max-image-preview:large">
                <meta name="googlebot" content="index, follow">

                <link rel="canonical" href="andreapeverelli.com/phx">

                <meta name="theme-color" content="#984061">
                <meta name="color-scheme" content="light dark">

                <link rel="manifest" href="/site.webmanifest">

                <link
                    rel="icon"
                    href="/icons/favicon.ico"
                    sizes="any"
                >
                <link
                    rel="icon"
                    type="image/svg+xml"
                    href="/icons/favicon.svg"
                >

                <link
                    rel="icon"
                    type="image/png"
                    sizes="32x32"
                    href="/icons/favicon-32x32.png"
                >
                <link
                    rel="icon"
                    type="image/png"
                    sizes="16x16"
                    href="/icons/favicon-16x16.png"
                >

                <link
                    rel="apple-touch-icon"
                    sizes="180x180"
                    href="/icons/apple-touch-icon.png"
                >

                <link
                    rel="icon"
                    type="image/png"
                    sizes="192x192"
                    href="/icons/icon-192.png"
                >
                <link
                    rel="icon"
                    type="image/png"
                    sizes="512x512"
                    href="/icons/icon-512.png"
                >

                <meta
                    name="msapplication-TileColor"
                    content="#984061"
                >
                <meta
                    name="msapplication-TileImage"
                    content="/icons/mstile-144x144.png"
                >

                <meta property="og:type" content="website">
                <meta property="og:url" content="andreapeverelli.com/phx">
                <meta property="og:title" content="Test">
                <meta property="og:description" content="Test render">
                <meta
                    property="og:image"
                    content="andreapeverelli.com/phx/icons/og-image.png"
                >
                <meta property="og:image:width" content="1200">
                <meta property="og:image:height" content="630">
                <meta property="og:locale" content="en,it">

                <meta name="twitter:card" content="summary_large_image">
                <meta name="twitter:url" content="andreapeverelli.com/phx">
                <meta name="twitter:title" content="Test">
                <meta name="twitter:description" content="Test render">
                <meta
                    name="twitter:image"
                    content="andreapeverelli.com/phx/icons/og-image.png"
                >

                <script type="application/ld+json">
                    {
                        "@context": "https://schema.org",
                        "@type": "WebSite",
                        "name": "phx-core",
                        "url": "andreapeverelli.com/phx"
                    }
                </script>
            </head>
            <body>
                Test
            </body>
            </html>
            HTML,
            $render,
        );
    }
}

/** @extends Component<Props> */
final class AppTestComponent extends Component
{
    protected static function getName(): string
    {
        return "test-component";
    }

    protected static function getTemplatePath(): string
    {
        return "";
    }

    public function addComponentTypo(Typo $typo): void
    {
        $this->addTypo(typo: $typo);
    }
}
