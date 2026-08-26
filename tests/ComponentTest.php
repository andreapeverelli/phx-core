<?php

/*
 * Copyright (c) 2026 Andrea Peverelli
 * https://github.com/andreapeverelli/phx-core.git
 *
 * SPDX-License-Identifier: GPL-3.0-only
 */

/**
 * @file ComponentTest.php
 * @brief Base component unit test.
 */

declare(strict_types=1);

namespace Tests;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;
use Monolog\Logger;
use AndreaPeverelli\PhxCore\App;
use AndreaPeverelli\PhxCore\Props;
use AndreaPeverelli\PhxCore\Component;
use AndreaPeverelli\PhxCore\Palette\Color;
use AndreaPeverelli\PhxCore\Palette\BaseColor;
use AndreaPeverelli\PhxCore\Palette\ColorRole;
use AndreaPeverelli\PhxCore\Css\CssProperty;
use AndreaPeverelli\PhxCore\Typography\Typo;
use AndreaPeverelli\PhxCore\Typography\TypoRole;
use AndreaPeverelli\PhxCore\Typography\TypoSubRole;
use AndreaPeverelli\PhxCore\Exception\FileDoesNotExists;

/**
 * @template PropsObject of \AndreaPeverelli\PhxCore\Props
 * @phpstan-import-type ComponentsProps from \AndreaPeverelli\PhxCore\Component
 * @phpstan-import-type NormalizedAttributes from \AndreaPeverelli\PhxCore\Component
 * @phpstan-import-type Settings from \AndreaPeverelli\PhxCore\App
 */

final class ComponentTest extends TestCase
{
    /*
     * TESTS:
     *  - Setup
     *  - Get attributes
     *  - Build
     *  - Add color
     *  - Add typo
     */

    #[Test]
    #[TestDox("Setting up component")]
    public function setupComponent(): void
    {
        /** @var Settings */
        $settings = [
            "palette" => json_decode((string) file_get_contents(__DIR__ . "/../settings/default.palette.json"), true),
            "typescale" => json_decode((string) file_get_contents(__DIR__ . "/../settings/default.typescale.json"), true),
        ];

        $id = uniqid();

        /*
         * 1 => single props test
         * 2 => multi props test
         */

        /**************************************************
         * Setup                                          *
         **************************************************/

        $component1 = new TestComponent();
        $props1 = $component1->setupComponent(
            props: new Props(attributes: ["id" => $id]),
            app: new App(
                logger: new Logger(""),
                settings: $settings,
            ),
        );


        $component2 = new TestComponent();
        $props2 = $component2->setupComponent(
            props: [
                "default" => new Props(attributes: ["id" => $id]),
                "test" => new Props(attributes: ["id" => $id]),
            ],
            app: new App(
                logger: new Logger(""),
                settings: $settings,
            ),
        );

        /**************************************************
         * Tests                                          *
         **************************************************/

        $this->assertEquals(
            ["default" => new Props(["id" => $id])],
            $props1,
            "Checking props",
        );

        $this->assertEquals(
            [
                "default" => new Props(["id" => $id]),
                "test" => new Props(["id" => $id]),
            ],
            $props2,
            "Checking props",
        );

    }

    #[Test]
    #[TestDox("Getting component attributes")]
    public function getComponentAttributes(): void
    {
        /** @var Settings */
        $settings = [
            "palette" => json_decode((string) file_get_contents(__DIR__ . "/../settings/default.palette.json"), true),
            "typescale" => json_decode((string) file_get_contents(__DIR__ . "/../settings/default.typescale.json"), true),
        ];

        $id = uniqid();

        /*
         * 1 => Full attributes shape
         * 2 => No attributes
         */

        /**************************************************
         * Setup                                          *
         **************************************************/

        $component1 = new TestComponent();
        $component1->setupComponent(
            props: new Props(attributes: [
                "id" => $id,
                "class" => ["test1", "test2"],
                "test-key" => "test-value",
            ]),
            app: new App(
                logger: new Logger(""),
                settings: $settings,
            ),
        );

        $attributes1 = $component1->getComponentAttributes();

        $component2 = new TestComponent();
        $component2->setupComponent(
            props: new Props(),
            app: new App(
                logger: new Logger(""),
                settings: $settings,
            ),
        );

        $attributes2 = $component2->getComponentAttributes();

        /**************************************************
         * Tests                                          *
         **************************************************/

        $this->assertSame(
            [
                ["key" => "id", "value" => $id],
                ["key" => "class", "value" => "test1 test2"],
                ["key" => "test-key", "value" => "test-value"],
            ],
            $attributes1,
            "Checking attributes",
        );
        $this->assertTrue(
            $attributes2[0]["key"] === "id",
            "Checking attributes",
        );
    }

    #[Test]
    #[TestDox("Build component")]
    public function buildComponent(): void
    {
        /** @var Settings */
        $settings = [
            "palette" => json_decode((string) file_get_contents(__DIR__ . "/../settings/default.palette.json"), true),
            "typescale" => json_decode((string) file_get_contents(__DIR__ . "/../settings/default.typescale.json"), true),
        ];

        $id = uniqid();

        /*
         * 1 => no template
         * 2 => valid template path
         * 3 => invalid template path
         */

        /**************************************************
         * Setup                                          *
         **************************************************/

        $component1 = new TestComponent();
        $component1->setupComponent(
            props: new Props(),
            app: new App(
                logger: new Logger(""),
                settings: $settings,
            ),
        );

        $component1->buildComponent();

        $component2 = new TestComponentTemplate();
        $component2->setupComponent(
            props: new Props(),
            app: new App(
                logger: new Logger(""),
                settings: $settings,
            ),
        );

        $component2->setComponentContext(context: (object) ["attributes" => ["id" => $id]]);
        $component2->buildComponent();

        $component3 = new TestComponentBrokenTemplate();
        $component3->setupComponent(
            props: new Props(),
            app: new App(
                logger: new Logger(""),
                settings: $settings,
            ),
        );

        /**************************************************
         * Tests                                          *
         **************************************************/

        $this->assertEquals(
            "",
            $component1->html,
            "Checking build",
        );

        $this->assertEquals(
            "Test Build id=$id\n",
            $component2->html,
            "Checking build",
        );

        $this->expectException(FileDoesNotExists::class);
        $component3->buildComponent();
    }

    #[Test]
    #[TestDox("Adding a color")]
    public function addColor(): void
    {
        /** @var Settings */
        $settings = [
            "palette" => json_decode((string) file_get_contents(__DIR__ . "/../settings/default.palette.json"), true),
            "typescale" => json_decode((string) file_get_contents(__DIR__ . "/../settings/default.typescale.json"), true),
        ];

        /*
         * test all color roles
         */

        foreach (ColorRole::cases() as $color_role) {

            /**************************************************
             * Setup                                          *
             **************************************************/

            $component = new TestComponent();
            $component->setupComponent(
                props: new Props(),
                app: new App(
                    logger: new Logger(""),
                    settings: $settings,
                ),
            );

            $component->addComponentColor(
                color: new Color(
                    base: BaseColor::PRIMARY,
                    role: $color_role,
                ),
                css_property: CssProperty::COLOR,
            );

            $attributes = $component->getComponentAttributes();

            /**************************************************
             * Tests                                          *
             **************************************************/

            if ($color_role === ColorRole::ON_ROLE) {
                $this->assertSame(
                    str_replace([" ", "\t", "\n", "\r"], "", <<<CSS
                    .primary-on-role-color {
                        color: "#ffffff";
                        color: "color(display-p3 1.00 1.00 1.00)";
                        color: "color(rec2020 1.00 1.00 1.00)";

                        @media (prefers-contrast: more) {
                            color: "#ffffff";
                            color: "color(display-p3 1.00 1.00 1.00)";
                            color: "color(rec2020 1.00 1.00 1.00)";
                        }	

                        @media (prefers-color-scheme: dark) {
                            color: "#5e1133";
                            color: "color(display-p3 0.34 0.09 0.20)";
                            color: "color(rec2020 0.34 0.16 0.24)";

                            @media (prefers-contrast: more) {
                                color: "#000000";
                                color: "color(display-p3 0.00 0.00 0.00)";
                                color: "color(rec2020 0.00 0.00 0.00)";
                            }
                        }
                    }
                    CSS),
                    str_replace([" ", "\t", "\n", "\r"], "", $component->css[0]),
                    "Checking CSS {$color_role->value}",
                );
            }

            $this->assertTrue(
                in_array(
                    ["key" => "class", "value" => "primary-{$color_role->value}-color"],
                    $attributes,
                ),
                "Checking class {$color_role->value}",
            );
        }
    }

    #[Test]
    #[TestDox("Adding a font")]
    public function addFont(): void
    {
        /** @var Settings */
        $settings = [
            "palette" => json_decode((string) file_get_contents(__DIR__ . "/../settings/default.palette.json"), true),
            "typescale" => json_decode((string) file_get_contents(__DIR__ . "/../settings/default.typescale.json"), true),
        ];

        /**************************************************
         * Setup                                          *
         **************************************************/

        $component = new TestComponent();
        $component->setupComponent(
            props: new Props(),
            app: new App(
                logger: new Logger(""),
                settings: $settings,
            ),
        );

        $component->addComponentTypo(
            typo: new Typo(
                role: TypoRole::DISPLAY,
                sub_role: TypoSubRole::LARGE,
            ),
            content: "",
        );

        $attributes = $component->getComponentAttributes();

        /**************************************************
         * Tests                                          *
         **************************************************/

        $this->assertSame(
            [<<<CSS
			.display-large {
				font-family: "phx-heading";
				font-size: "57";
				font-weight: "400";
				line-height: "64";
				letter-spacing: "0";
			}
			CSS],
            $component->css,
            "Checking CSS",
        );

        $this->assertTrue(
            in_array(
                ["key" => "class", "value" => "display-large"],
                $attributes,
            ),
            "Checking class",
        );

        $this->assertTrue(
            in_array(
                ["font-family" => "phx-heading", "italic" => false],
                $component->fonts,
            ),
            "Checking font list",
        );
    }
}

/**
 * @extends Component<Props>
 * @phpstan-import-type NormalizedAttributes from \AndreaPeverelli\PhxCore\Component
 * @phpstan-type ComponentsProps array<string, Props>
 */
final class TestComponent extends Component
{
    final protected static function getName(): string
    {
        return "test-component";
    }

    final protected static function getTemplatePath(): string
    {
        return "";
    }

    /**
     * @param Props|ComponentsProps $props
     *
     * @return ComponentsProps
     */
    public function setupComponent(object|array $props, App $app): array
    {
        return $this->setup(props: $props, app: $app);
    }

    final public function setComponentContext(object $context): void
    {
        $this->context = $context;
    }

    /** @return NormalizedAttributes */
    public function getComponentAttributes(): array
    {
        return $this->getAttributes();
    }

    public function buildComponent(): void
    {
        $this->build();
    }

    public function addComponentColor(
        Color $color,
        CssProperty $css_property,
        string $component_id = "default",
    ): void {
        $this->addColor(
            color: $color,
            css_property: $css_property,
            component_id: $component_id,
        );
    }

    public function addComponentTypo(
        Typo $typo,
        string $content,
        string $component_id = "default",
    ): void {
        $this->addTypo(
            typo: $typo,
            content: $content,
            component_id: $component_id,
        );
    }
}

/**
 * @extends Component<Props>
 * @phpstan-type ComponentsProps array<string, Props>
 */
final class TestComponentTemplate extends Component
{
    final protected static function getName(): string
    {
        return "test-component";
    }

    final protected static function getTemplatePath(): string
    {
        return __DIR__ . "/build.component.test.mustache";
    }

    /**
     * @param Props|ComponentsProps $props
     *
     * @return ComponentsProps
     */
    public function setupComponent(object|array $props, App $app): array
    {
        return $this->setup(props: $props, app: $app);
    }

    public function buildComponent(): void
    {
        $this->build();
    }

    public function setComponentContext(object $context): void
    {
        $this->context = $context;
    }
}

/**
 * @extends Component<Props>
 * @phpstan-type ComponentsProps array<string, Props>
 */
final class TestComponentBrokenTemplate extends Component
{
    final protected static function getName(): string
    {
        return "test-component";
    }

    final protected static function getTemplatePath(): string
    {
        return __DIR__ . "/path.doesnt.exists.test.mustache";
    }

    /**
     * @param Props|ComponentsProps $props
     *
     * @return ComponentsProps
     */
    public function setupComponent(object|array $props, App $app): array
    {
        return $this->setup(props: $props, app: $app);
    }

    public function buildComponent(): void
    {
        $this->build();
    }

    public function setComponentContext(object $context): void
    {
        $this->context = $context;
    }
}
