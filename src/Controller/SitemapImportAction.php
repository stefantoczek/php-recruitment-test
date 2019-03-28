<?php

namespace Snowdog\DevTest\Controller;

/**
 * Class SitemapImportAction
 *
 * @package Snowdog\DevTest\Controller
 */
class SitemapImportAction
{

    public function execute()
    {
        include __DIR__ . '/../view/sitemap_import.phtml';
    }
}