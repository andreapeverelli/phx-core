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

namespace Tests;

use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\Attributes\TestDox;

use AndreaPeverelli\PhxCore\Component;

final class ComponentTest extends TestCase
{
	#[Test]
	#[TestDox("Setting up component")]
	public function setupComponent(): void
	{
		$id = uniqid();

		$component = new TestComponent();
		$props = $component->setupComponent(
			props: (object)["attributes" => ["id" => $id]],
			template: "Test Setup",
		);

		$this->assertEquals(
			["default" => (object)["attributes" => ["id" => $id]]],
			$props,
			"Checking props",
		);

		$this->assertSame(
			"Test Setup",
			$component->template,
			"Checking mustache template",
		);
	}

	#[Test]
	#[TestDox("Getting component attributes")]
	public function getComponentAttributes(): void
	{
		$id = uniqid();

		$component = new TestComponent();
		$component->setupComponent(
			props: (object)["attributes" => ["id" => $id]],
			template: "Test Get Attributes",
		);

		$attributes = $component->getComponentAttributes();

		$this->assertSame(
			[["key" => "id", "value" => $id]],
			$attributes,
			"Checking attributes",
		);
	}

	#[Test]
	#[TestDox("Build component")]
	public function buildComponent(): void
	{
		$id = uniqid();

		$component = new TestComponent();
		$component->setupComponent(
			props: (object)[],
			template: "Test Build id={{attributes.id}}",
		);

		$component->context = (object)["attributes" => ["id" => $id]];
		$component->buildComponent();

		$this->assertEquals(
			"Test Build id=$id",
			$component->html,
			"Checking build",
		);
	}
}

final class TestComponent extends Component
{
	public function setupComponent(object $props, string $template): array
	{
		return $this->setup(props: $props, template: $template);
	}

	public function getComponentAttributes(): array
	{
		return $this->getAttributes();
	}

	public function buildComponent(): void
	{
		$this->build();
	}
}
