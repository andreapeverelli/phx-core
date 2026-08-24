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
use Monolog\Handler\TestHandler;
use Monolog\Logger;
use AndreaPeverelli\PhxCore\App;
use AndreaPeverelli\PhxCore\Component;
use AndreaPeverelli\PhxCore\Palette\Color;
use AndreaPeverelli\PhxCore\Palette\BaseColor;
use AndreaPeverelli\PhxCore\Palette\ColorRole;
use AndreaPeverelli\PhxCore\Css\CssProperty;
use AndreaPeverelli\PhxCore\Typography\Typo;
use AndreaPeverelli\PhxCore\Typography\TypoRole;
use AndreaPeverelli\PhxCore\Typography\TypoSubRole;

final class ComponentTest extends TestCase
{
    #[Test]
    #[TestDox("Setting up component")]
    public function setupComponent(): void
    {
        $logger = new Logger("test");
        $handler = new TestHandler();
        $logger->pushHandler($handler);

        $id = uniqid();

        $component = new TestComponent();
        $props = $component->setupComponent(
            props: (object) ["attributes" => ["id" => $id]],
            template: "Test Setup",
            app: new App(
                logger: $logger,
                settings: [],
            ),
        );

        $this->assertTrue($handler->hasInfo("Setting up"));
        $this->assertTrue($handler->hasDebug("Setting up state"));

        $this->assertEquals(
            ["default" => (object) ["attributes" => ["id" => $id]]],
            $props,
            "Checking props",
        );
    }

    #[Test]
    #[TestDox("Setting up a component with multiple props")]
    public function setupMultiPropsComponent(): void
    {
        $logger = new Logger("test");
        $handler = new TestHandler();
        $logger->pushHandler($handler);

        $id = uniqid();

        $component = new TestComponent();
        $props = $component->setupComponent(
            props: [
                "default" => (object) ["attributes" => ["id" => $id]],
                "test" => (object) ["attributes" => ["id" => $id]],
            ],
            template: "Test Setup",
            app: new App(
                logger: $logger,
                settings: [],
            ),
        );


        $this->assertTrue($handler->hasInfo("Setting up"));
        $this->assertTrue($handler->hasDebug("Setting up state"));

        $this->assertEquals(
            [
                "default" => (object) ["attributes" => ["id" => $id]],
                "test" => (object) ["attributes" => ["id" => $id]],
            ],
            $props,
            "Checking props",
        );
    }

    #[Test]
    #[TestDox("Getting component attributes")]
    public function getComponentAttributes(): void
    {
        $logger = new Logger("test");
        $handler = new TestHandler();
        $logger->pushHandler($handler);
        $id = uniqid();

        $component = new TestComponent();
        $component->setupComponent(
            props: (object) ["attributes" => [
                "id" => $id,
                "class" => ["test1", "test2"],
                "test-key" => "test-value",
            ]],
            template: "Test Get Attributes",
            app: new App(
                logger: $logger,
                settings: [],
            ),
        );

        $attributes = $component->getComponentAttributes();

        $this->assertTrue($handler->hasInfo("Getting attributes"));

        $this->assertSame(
            [
                ["key" => "id", "value" => $id],
                ["key" => "class", "value" => "test1 test2"],
                ["key" => "test-key", "value" => "test-value"],
            ],
            $attributes,
            "Checking attributes",
        );

        $component = new TestComponent();
        $component->setupComponent(
            props: (object) ["attributes" => []],
            template: "Test Get Attributes",
            app: new App(
                logger: $logger,
                settings: [],
            ),
        );

        $attributes = $component->getComponentAttributes();

        $this->assertTrue($handler->hasInfo("Getting attributes"));

        $this->assertTrue(
            $attributes[0]["key"] === "id" && is_string($attributes[0]["value"]),
            "Checking attributes",
        );
    }

    #[Test]
    #[TestDox("Build component")]
    public function buildComponent(): void
    {
        $logger = new Logger("test");
        $handler = new TestHandler();
        $logger->pushHandler($handler);
        $id = uniqid();

        $component = new TestComponent();
        $component->setupComponent(
            props: (object) [],
            template: "Test Build id={{attributes.id}}",
            app: new App(
                logger: $logger,
                settings: [],
            ),
        );

        $component->setComponentContext(context: (object) ["attributes" => ["id" => $id]]);
        $component->buildComponent();

        $this->assertTrue($handler->hasInfo("Building component"));

        $this->assertEquals(
            "Test Build id=$id",
            $component->html,
            "Checking build",
        );
    }

    #[Test]
    #[TestDox("Adding a color")]
    public function addColor(): void
    {
        $logger = new Logger("test");
        $handler = new TestHandler();
        $logger->pushHandler($handler);

        foreach (ColorRole::cases() as $color_role) {
            $component = new TestComponent();
            $component->setupComponent(
                props: [],
                template: "Test Color",
                app: new App(
                    logger: $logger,
                    settings: [
                        "palette" => json_decode(file_get_contents(__DIR__ . "/../settings/default.palette.json"), true),
                    ],
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

            $component->buildComponent();

            $this->assertTrue($handler->hasInfo("Adding color"));

            if ($color_role === ColorRole::ON_ROLE) {
                $this->assertSame(
                    [<<<CSS
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
					CSS],
                    $component->css,
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
        $logger = new Logger("test");
        $handler = new TestHandler();
        $logger->pushHandler($handler);

        $component = new TestComponent();
        $component->setupComponent(
            props: [],
            template: "Test Font",
            app: new App(
                logger: $logger,
                settings: [
                    "typescale" => json_decode(file_get_contents(__DIR__ . "/../settings/default.typescale.json"), true),
                ],
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

        $component->buildComponent();

        $this->assertTrue($handler->hasInfo("Adding typo"));
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
 * @phpstan-import-type PropsObject from \AndreaPeverelli\PhxCore\Component
 * @phpstan-import-type Props from \AndreaPeverelli\PhxCore\Component
 * @phpstan-import-type Attribute from \AndreaPeverelli\PhxCore\Component
 */
final class TestComponent extends Component
{
    /**
     * @param PropsObject|Props $props
     *
     * @return Props
     */
    public function setupComponent(object|array $props, string $template, App $app): array
    {
        return $this->setup(props: $props, template: $template, app: $app);
    }
    /**
     * @return Attribute
     */
    public function getComponentAttributes(): array
    {
        return $this->getAttributes();
    }

    public function buildComponent(): void
    {
        $this->build();
    }

    public function setComponentContext(object $context): void
    {
        $this->setContext(context: $context);
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
