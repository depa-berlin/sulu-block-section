<?php

declare(strict_types=1);

namespace Depa\SuluBlockSectionBundle\Tests\Unit\DependencyInjection;

use Depa\SuluBlockSectionBundle\DependencyInjection\SuluBlockSectionExtension;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class SuluBlockSectionExtensionTest extends TestCase
{
    private ContainerBuilder $container;
    private SuluBlockSectionExtension $extension;

    protected function setUp(): void
    {
        $this->container = new ContainerBuilder();
        $this->extension = new SuluBlockSectionExtension();
    }

    public function testLoadSetsBundleMetadataParameter(): void
    {
        $this->extension->load([], $this->container);
        self::assertTrue($this->container->hasParameter('sulu_block_section.bundle_metadata'));
    }

    public function testBundleMetadataHasRequiredKeys(): void
    {
        $this->extension->load([], $this->container);
        $meta = $this->container->getParameter('sulu_block_section.bundle_metadata');
        self::assertIsArray($meta);
        self::assertArrayHasKey('bundle', $meta);
        self::assertArrayHasKey('package', $meta);
        self::assertArrayHasKey('blocks', $meta);
        self::assertArrayHasKey('children', $meta);
    }

    public function testBundleMetadataContainsCorrectBundleName(): void
    {
        $this->extension->load([], $this->container);
        $meta = $this->container->getParameter('sulu_block_section.bundle_metadata');
        self::assertIsArray($meta);
        self::assertSame('SuluBlockSectionBundle', $meta['bundle']);
    }

    public function testBundleMetadataContainsCorrectPackageName(): void
    {
        $this->extension->load([], $this->container);
        $meta = $this->container->getParameter('sulu_block_section.bundle_metadata');
        self::assertIsArray($meta);
        self::assertSame('depa/sulu-block-section', $meta['package']);
    }

    public function testBundleMetadataContainsAtLeastOneBlock(): void
    {
        $this->extension->load([], $this->container);
        $meta = $this->container->getParameter('sulu_block_section.bundle_metadata');
        self::assertIsArray($meta);
        self::assertNotEmpty($meta['blocks']);
    }

    public function testBlocksAreSortedAndUnique(): void
    {
        $this->extension->load([], $this->container);
        $meta = $this->container->getParameter('sulu_block_section.bundle_metadata');
        self::assertIsArray($meta);
        $blocks = $meta['blocks'];
        $sorted = $blocks;
        sort($sorted);
        self::assertSame($sorted, $blocks, 'blocks must be sorted');
        self::assertSame(array_unique($blocks), $blocks, 'blocks must be unique');
    }

    public function testKnownSectionBlocksArePresent(): void
    {
        $this->extension->load([], $this->container);
        $meta = $this->container->getParameter('sulu_block_section.bundle_metadata');
        self::assertIsArray($meta);

        foreach (['block--section', 'block--section-image', 'block--section-youtube', 'block--container'] as $expected) {
            self::assertContains($expected, $meta['blocks']);
        }
    }

    public function testChildrenValuesAreArraysOfStrings(): void
    {
        $this->extension->load([], $this->container);
        $meta = $this->container->getParameter('sulu_block_section.bundle_metadata');
        self::assertIsArray($meta);

        foreach ($meta['children'] as $parent => $kids) {
            self::assertIsArray($kids, "Children of '{$parent}' must be an array");
            foreach ($kids as $child) {
                self::assertIsString($child);
            }
        }
    }

    public function testSectionHasChildrenFromXml(): void
    {
        $this->extension->load([], $this->container);
        $meta = $this->container->getParameter('sulu_block_section.bundle_metadata');
        self::assertIsArray($meta);

        self::assertArrayHasKey('block--section', $meta['children']);
        self::assertContains('block--container', $meta['children']['block--section']);
    }

    public function testContainerHasChildrenFromXml(): void
    {
        $this->extension->load([], $this->container);
        $meta = $this->container->getParameter('sulu_block_section.bundle_metadata');
        self::assertIsArray($meta);

        self::assertArrayHasKey('block--container', $meta['children']);
        self::assertNotEmpty($meta['children']['block--container']);
    }
}
