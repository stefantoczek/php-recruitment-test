<?php

namespace Snowdog\DevTest\Menu;

/**
 * Class SitemapImportMenu
 *
 * @package Snowdog\DevTest\Menu
 */
class SitemapImportMenu extends AbstractMenu
{

    /**
     * @return bool
     */
    public function isActive()
    {
        return $_SERVER['REQUEST_URI'] == '/sitemap-import';
    }

    /**
     * @return string
     */
    public function getHref()
    {
        return '/sitemap-import';
    }

    /**
     * @return string
     */
    public function getLabel()
    {
        return 'Sitemap Import';
    }
}