<?php

declare(strict_types=1);

namespace Depa\SuluBlockSectionBundle\Tests\Unit;

use Depa\SuluBlockSectionBundle\SuluBlockSectionBundle;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class SuluBlockSectionBundleTest extends TestCase
{
    private ContainerBuilder $container;
    private SuluBlockSectionBundle $bundle;

    protected function setUp(): void
    {
        $this->container = new ContainerBuilder();
        $this->bundle = new SuluBlockSectionBundle();
    }

    private function load(): void
    {
        $this->bundle->getContainerExtension()->load([], $this->container);
    }

    public function testLoadSetsBundleMetadataParameter(): void
    {
        $this->load();
        self::assertTrue($this->container->hasParameter('sulu_block_section.bundle_metadata'));
    }

    public function testBundleMetadataHasRequiredKeys(): void
    {
        $this->load();
        $meta = $this->container->getParameter('sulu_block_section.bundle_metadata');
        self::assertIsArray($meta);
        self::assertArrayHasKey('bundle', $meta);
        self::assertArrayHasKey('package', $meta);
        self::assertArrayHasKey('blocks', $meta);
        self::assertArrayHasKey('children', $meta);
    }

    public function testBundleMetadataContainsCorrectBundleName(): void
    {
        $this->load();
        $meta = $this->container->getParameter('sulu_block_section.bundle_metadata');
        self::assertIsArray($meta);
        self::assertSame('SuluBlockSectionBundle', $meta['bundle']);
    }

    public function testBundleMetadataContainsCorrectPackageName(): void
    {
        $this->load();
        $meta = $this->container->getParameter('sulu_block_section.bundle_metadata');
        self::assertIsArray($meta);
        self::assertSame('depa/sulu-block-section', $meta['package']);
    }

    public function testBundleMetadataContainsAtLeastOneBlock(): void
    {
        $this->load();
        $meta = $this->container->getParameter('sulu_block_section.bundle_metadata');
        self::assertIsArray($meta);
        self::assertNotEmpty($meta['blocks']);
    }

    public function testBlocksAreSortedAndUnique(): void
    {
        $this->load();
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
        $this->load();
        $meta = $this->container->getParameter('sulu_block_section.bundle_metadata');
        self::assertIsArray($meta);

        foreach (['block--section', 'block--section-image', 'block--section-youtube', 'block--container'] as $expected) {
            self::assertContains($expected, $meta['blocks']);
        }
    }

    public function testChildrenValuesAreArraysOfStrings(): void
    {
        $this->load();
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
        $this->load();
        $meta = $this->container->getParameter('sulu_block_section.bundle_metadata');
        self::assertIsArray($meta);

        self::assertArrayHasKey('block--section', $meta['children']);
        self::assertContains('block--container', $meta['children']['block--section']);
    }

    public function testContainerHasChildrenFromXml(): void
    {
        $this->load();
        $meta = $this->container->getParameter('sulu_block_section.bundle_metadata');
        self::assertIsArray($meta);

        self::assertArrayHasKey('block--container', $meta['children']);
        self::assertNotEmpty($meta['children']['block--container']);
    }
}
