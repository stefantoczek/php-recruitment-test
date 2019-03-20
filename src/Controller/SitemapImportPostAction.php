<?php

namespace Snowdog\DevTest\Controller;

use Snowdog\DevTest\Component\DatabaseWebsiteSetter;
use Stefantoczek\SitemapParser\XmlDataStream\XmlUploadFileStream;
use Snowdog\DevTest\Model\UserManager;
use Stefantoczek\SitemapParser\SitemapParser;

/**
 * Class SitemapImportPostAction
 *
 * @package Snowdog\DevTest\Controller
 */
class SitemapImportPostAction
{

    private $sitemapParser;
    private $user;
    private $databaseWebsiteSetter;

    /**
     * SitemapImportPostAction constructor.
     *
     * @param \Stefantoczek\SitemapParser\SitemapParser        $sitemapParser
     * @param \Snowdog\DevTest\Model\UserManager               $userManager
     * @param \Snowdog\DevTest\Component\DatabaseWebsiteSetter $databaseWebsiteSetter
     */
    public function __construct(
        SitemapParser $sitemapParser,
        UserManager $userManager,
        DatabaseWebsiteSetter $databaseWebsiteSetter
    ) {
        $this->user = $userManager->getLoggedUser();
        $this->sitemapParser = $sitemapParser;
        $this->databaseWebsiteSetter = $databaseWebsiteSetter;
    }

    public function execute()
    {
        if ($this->user === null) {
            $_SESSION['flash'] = 'In order to import sitemaps you need to be logged in!';
        } else {
            $this->processUploadedFile();
        }
        include __DIR__ . '/../view/sitemap_import.phtml';
    }

    private function processUploadedFile()
    {
        try {
            $uploadFileStream = new XmlUploadFileStream($_FILES['sitemap']);
            $this->sitemapParser
                ->loadFromStream($uploadFileStream)
                ->setWebsiteDatabaseSetter($this->databaseWebsiteSetter)
                ->insertDataToDatabase();
            $_SESSION['flash'] = 'Successfully imported website!';
        } catch (\Exception $e) {
            $_SESSION['flash'] = 'Exception occured, code: ' . $e->getMessage();
        }
    }
}