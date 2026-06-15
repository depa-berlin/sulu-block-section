<?php

declare(strict_types=1);

namespace Depa\SuluBlockSectionBundle\DependencyInjection;

use Depa\SuluBlockHelperBundle\DependencyInjection\AbstractBlockExtension;

class SuluBlockSectionExtension extends AbstractBlockExtension
{
    protected function getBundleName(): string
    {
        return 'SuluBlockSectionBundle';
    }

    protected function getPackageName(): string
    {
        return 'depa/sulu-block-section';
    }

    protected function getMetadataParameterName(): string
    {
        return 'sulu_block_section.bundle_metadata';
    }

    protected function getSuluAdminTemplateKey(): string
    {
        return 'sulu_block_section';
    }
}
