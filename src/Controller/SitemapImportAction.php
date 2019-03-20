<?php

namespace Snowdog\DevTest\Controller;

use Snowdog\DevTest\Model\UserManager;

/**
 * Class SitemapImportAction
 *
 * @package Snowdog\DevTest\Controller
 */
class SitemapImportAction
{
    private $user;

    /**
     * SitemapImportAction constructor.
     *
     * @param \Snowdog\DevTest\Model\UserManager $userManager
     */
    public function __construct(UserManager $userManager)
    {
        $this->user = $userManager->getLoggedUser();
    }

    public function execute()
    {
        include __DIR__ . '/../view/sitemap_import.phtml';
    }
}