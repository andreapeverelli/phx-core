<?php

/*
 * Copyright (c) 2026 Andrea Peverelli
 * https://github.com/andreapeverelli/phx-core.git
 *
 * SPDX-License-Identifier: GPL-3.0-only
 */

/**
 * @file Component.php
 * @brief Base component implementation.
 */

namespace AndreaPeverelli\PhxCore;

use Mustache\Engine;

/**
 * Base class for all PHX components.
 *
 * It manages mustache templates rendering, PHX building and properties normalization.
 */
abstract class Component
{
	/**
	 * Registered props indexed by component_id
	 *
	 * @var array<string, object>
	 */
	public array $props = ["default" => []];

	/**
	 * Normalized HTML attributes indexed by component_id
	 *
	 * @var array<string, array<array{key: string, value: string}>>
	 */
	public array $attributes = ["default" => []];
	public object $context;

	public string $html = "";

	/**
	 * Setup the component registering the props and the mustache template; than returns the props.
	 *
	 * @param object|array<string, object> $props
	 * @param string $template
	 *
	 * @return array<string, object>
	 */
	final protected function setup(object|array $props, string $template): array
	{
		if(is_object($props)) {
			$this->props["default"] = $props;
			$props = ["default" => $props];
		} else {
			foreach($props as $component_id => $component_props) {
				$this->props[$component_id] = $component_props;
			}
		}

		$this->template = $template;

		return $props;
	}

	/**
	 * Normalize and reutrn a component attributes.
	 *
	 * @param null|string $component_id
	 */
	final protected function getAttributes(?string $component_id = null): array
	{
		$this->buildAttributes(component_id: $component_id ?? "default");

		return $this->attributes[$component_id ?? "default"];
	}

	/**
	 * Normalize a component attributes based on the component props->attributes.
	 *
	 * @param string $component_id
	 */
	private function buildAttributes(string $component_id): void
	{
		if(isset($this->props[$component_id])) {
			$is_id_set = false;
			$this->attributes[$component_id] = [];

			foreach($this->props[$component_id]->attributes as $key => $value) {
				if($key === "id") $is_id_set = true;

				if($key === "class")
					array_push(
						$this->attributes[$component_id], [
							"key" => $key,
							"value" => implode(" ", $value),
						]
					);
				else array_push(
					$this->attributes[$component_id],
					["key" => $key, "value" => $value]
				);
			}

			if(!$is_id_set) array_push(
				$this->attributes[$component_id],
				["key" => "id", "value" => uniqid()],
			);
		} else $this->attributes[$component_id] = [["key" => "id", "value" => uniqid()]];
	}

	/**
	 * Build the component HTML, CSS and JS; provides a used fonts and color palette list too.
	 */
	final protected function build(): void
	{
		$this->render();
	}

	/**
	 * Render a mustache template based on the provided context.
	 */
	private function render(): void
	{
		$mustache = new Engine(["entity_flags" => ENT_QUOTES]);

		$this->html = $mustache->render($this->template, $this->context);
	}
}
