<?php

/*
 * Copyright (c) 2026 Andrea Peverelli
 * https://github.com/andreapeverelli/phx-core.git
 *
 * SPDX-License-Identifier: GPL-3.0-only
 */

/**
 * @file Component.php
 * @brief Base component implementation; it manages mustache templates rendering, PHX building and properties normalization.
 */

declare(strict_types=1);

namespace AndreaPeverelli\PhxCore;

use Mustache\Engine;
use AndreaPeverelli\PhxCore\App;
use AndreaPeverelli\PhxCore\Palette\Color;
use AndreaPeverelli\PhxCore\Palette\Theme;
use AndreaPeverelli\PhxCore\Palette\Contrast;
use AndreaPeverelli\PhxCore\Palette\Gamut;
use AndreaPeverelli\PhxCore\Typography\Typo;
use AndreaPeverelli\PhxCore\Css\CssProperty;
use AndreaPeverelli\PhxCore\Settings\Setting;

/**
 * @phpstan-type PropsObject \stdClass&object{
 *		attributes: array{
 *			id?: string,
 *			class?: array<int, string>,
 *			...<string, string>
 *		}
 * }
 * @phpstan-type Props array<string, PropsObject>
 *
 * @phpstan-type Attribute array<int, array{key: string, value: string}>
 *
 * @phpstan-import-type Settings from \AndreaPeverelli\PhxCore\App
 */

abstract class Component
{
    /**************************************************
     * Internal state                                 *
     **************************************************/

    /**
     * Registered props indexed by component_id
     *
     * @var Props
     */
    private array $props = [];

    /**
     * Normalized HTML attributes indexed by component_id
     *
     * @var array<string, Attribute>
     */
    private array $attributes = [];
    private object $context;
    /**
     * The component mustache template
     */
    private string $template = "";

    /**
     * PHX app data like settings and logger instance
     *
     * @var App
     */
    private App $app;

    /**************************************************
     * Build bundle                                   *
     **************************************************/

    public string $html = "";
    /** @var array<int, string> $css */
    public array $css = [];
    /** @var array<int, string> $js */
    public array $js = [];
    /** @var array<int, array{font-family: string, italic: bool}> $fonts */
    public array $fonts = [];

    /**************************************************
     * Internal state management processes            *
     **************************************************/

    /**
     * Setup the component registering the props and the mustache template; than returns the props.
     *
     * @param PropsObject|Props $props
     * @param string $template
     * @param App $app
     *
     * @return Props
     */
    final protected function setup(object|array $props, string $template, App &$app): array
    {
        $app->logger->info("Setting up");
        $app->logger->debug("Setting up state", [
            "props" => var_export($props, true),
            "template" => $template,
        ]);

        if (is_object($props)) {
            $this->props["default"] = $props;
            $props = ["default" => $props];
        } else {
            foreach ($props as $component_id => $component_props) {
                $this->props[$component_id] = $component_props;
            }
        }

        $this->template = $template;
        $this->app = $app;

        return $props;
    }

    /**
     * Normalize and reutrn a component attributes.
     *
     * @param string $component_id
     *
     * @return Attribute
     */
    final protected function getAttributes(string $component_id = "default"): array
    {
        $this->app->logger->info("Getting attributes", ["component_id" => $component_id]);

        $this->buildAttributes(component_id: $component_id);

        return $this->attributes[$component_id];
    }

    /**
     * Normalize a component attributes based on the component props->attributes.
     *
     * @param string $component_id
     */
    private function buildAttributes(string $component_id): void
    {
        $this->app->logger->info("Building attributes", ["component_id" => $component_id]);

        $is_id_set = false;
        $this->attributes[$component_id] = [];

        foreach ($this->props[$component_id]->attributes as $key => $value) {
            if ($key === "id") {
                $is_id_set = true;
            }

            if ($key === "class") {
                array_push(
                    $this->attributes[$component_id],
                    [
                        "key" => $key,
                        "value" => implode(" ", $value),
                    ],
                );
            } else {
                assert(is_string($value));

                array_push(
                    $this->attributes[$component_id],
                    ["key" => $key, "value" => $value],
                );
            }
        }

        if (!$is_id_set) {
            array_push(
                $this->attributes[$component_id],
                ["key" => "id", "value" => uniqid()],
            );
        }
    }

    /**
     * Context setter
     *
     * @param object $context
     */
    final protected function setContext(object $context): void
    {
        $this->app->logger->info("Setting context");

        $this->context = $context;
    }

    /**
     * Generate and register color related CSS and classnames.
     *
     * @param Color $color
     * @param CssProperty $css_property
     * @param string $component_id
     */
    final protected function addColor(Color $color, CssProperty $css_property, string $component_id = "default"): void
    {
        $this->app->logger->info("Adding color", [
            "color" => (string) $color,
            "css_property" => $css_property->value,
            "component_id" => $component_id,
        ]);

        $palette = $color->role->getTonesPalette();

        // Normalizing palette data to css color values
        /**
         * @var array<
         *     value-of<Theme>,
         *     array<
         *         value-of<Contrast>,
         *         array<value-of<Gamut>, string>
         *     >
         * >
         */
        $color_values = [];
        foreach (Theme::cases() as $_theme) {
            $theme = $_theme->value;

            foreach (Contrast::cases() as $_contrast) {
                $contrast = $_contrast->value;

                foreach (Gamut::cases() as $_gamut) {
                    $gamut = $_gamut->value;

                    $value
                        = $this->app->settings[Setting::PALETTE->value][$color->base->value][$palette[$theme][$contrast]->value][$gamut];
                    if (
                        $gamut === Gamut::REC2020->value
                        || $gamut === Gamut::DISPLAY_P3->value
                    ) {
                        assert(is_array($value));

                        $r = number_format($value["r"], 2);
                        $g = number_format($value["g"], 2);
                        $b = number_format($value["b"], 2);

                        $value = "color($gamut $r $g $b)";
                    }
                    assert(is_string($value));

                    $color_values[$theme][$contrast][$gamut] = $value;
                }
            }
        }

        $css_property = $css_property->value;
        $class = "{$color->base->value}-{$color->role->value}-{$css_property}";

        $css = <<<CSS
		.$class {
			$css_property: "{$color_values[Theme::LIGHT->value][Contrast::DEFAULT->value][Gamut::SRGB->value]}";
			$css_property: "{$color_values[Theme::LIGHT->value][Contrast::DEFAULT->value][Gamut::DISPLAY_P3->value]}";
			$css_property: "{$color_values[Theme::LIGHT->value][Contrast::DEFAULT->value][Gamut::REC2020->value]}";

			@media (prefers-contrast: more) {
				$css_property: "{$color_values[Theme::LIGHT->value][Contrast::HIGH->value][Gamut::SRGB->value]}";
				$css_property: "{$color_values[Theme::LIGHT->value][Contrast::HIGH->value][Gamut::DISPLAY_P3->value]}";
				$css_property: "{$color_values[Theme::LIGHT->value][Contrast::HIGH->value][Gamut::REC2020->value]}";
			}	

			@media (prefers-color-scheme: dark) {
				$css_property: "{$color_values[Theme::DARK->value][Contrast::DEFAULT->value][Gamut::SRGB->value]}";
				$css_property: "{$color_values[Theme::DARK->value][Contrast::DEFAULT->value][Gamut::DISPLAY_P3->value]}";
				$css_property: "{$color_values[Theme::DARK->value][Contrast::DEFAULT->value][Gamut::REC2020->value]}";

				@media (prefers-contrast: more) {
					$css_property: "{$color_values[Theme::DARK->value][Contrast::HIGH->value][Gamut::SRGB->value]}";
					$css_property: "{$color_values[Theme::DARK->value][Contrast::HIGH->value][Gamut::DISPLAY_P3->value]}";
					$css_property: "{$color_values[Theme::DARK->value][Contrast::HIGH->value][Gamut::REC2020->value]}";
				}
			}
		}
		CSS;

        // Register to the bundle
        $this->registerClass(class: $class, component_id: $component_id);
        array_push($this->css, $css);
    }

    /**
     * Generate and register typo related CSS and add the required font to the bundle font list
     *
     * @param Typo $typo
     * @param string $content
     * @param string $component_id
     */
    final protected function addTypo(
        Typo $typo,
        string $content,
        string $component_id = "default",
    ): void {
        $this->app->logger->info("Adding typo", [
            "typo" => (string) $typo,
            "component_id" => $component_id,
        ]);

        $italic = str_contains($content, "<i>") ? true : false;
        $this->app->logger->info($italic ? "Found italic content" : "No italic content");

        $role = $typo->role->value;
        $sub_role = $typo->sub_role->value;
        $emphasized = $typo->emphasized->value;

        $class = "{$role}-{$sub_role}";
        $font_family = $typo->role->getFontFamily();

        $typescale = $this->app->settings[Setting::TYPESCALE->value];

        $css = <<<CSS
		.$class {
			font-family: "$font_family";
			font-size: "{$typescale[$role][$sub_role]["font-size"]}";
			font-weight: "{$typescale[$role][$sub_role]["font-weight"][$emphasized]}";
			line-height: "{$typescale[$role][$sub_role]["line-height"]}";
			letter-spacing: "{$typescale[$role][$sub_role]["letter-spacing"]}";
		}
		CSS;

        // Register to the bundle
        $this->registerClass(class: $class, component_id: $component_id);
        array_push($this->css, $css);
        array_push($this->fonts, ["font-family" => $font_family, "italic" => $italic]);
    }

    /**
     * Add the classname to a component props's attribute
     *
     * @param string $class
     * @param string $component_id
     */
    final protected function registerClass(string $class, string $component_id = "default"): void
    {
        $this->app->logger->info("Registering class", [
            "class" => $class,
            "component_id" => $component_id,
        ]);

        $this->props[$component_id] ??= (object) ["attributes" => ["class" => []]];
        $this->props[$component_id]->attributes["class"] ??= [];

        array_push($this->props[$component_id]->attributes["class"], $class);
    }

    /**************************************************
     * Build bundle processes                         *
     **************************************************/

    /**
     * Build the component HTML, CSS and JS; provides a used fonts and color palette list too.
     */
    final protected function build(): void
    {
        $this->app->logger->info("Building component");

        $this->render();
    }

    /**
     * Render a mustache template based on the provided context.
     */
    private function render(): void
    {
        $this->app->logger->info("Rendering mustache template");

        $mustache = new Engine(["entity_flags" => ENT_QUOTES]);

        $this->context ??= (object) [];
        $this->html = $mustache->render($this->template, $this->context);
    }
}
