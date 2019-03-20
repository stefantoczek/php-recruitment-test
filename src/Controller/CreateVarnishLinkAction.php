<?php

namespace Snowdog\DevTest\Controller;

use Snowdog\DevTest\Model\UserManager;
use Snowdog\DevTest\Model\Varnish;
use Snowdog\DevTest\Model\VarnishManager;
use Snowdog\DevTest\Model\WebsiteManager;

/**
 * Class CreateVarnishLinkAction
 *
 * @package Snowdog\DevTest\Controller
 */
class CreateVarnishLinkAction
{
    /**
     * @var VarnishManager
     */
    private $varnishManager;
    /** @var WebsiteManager */
    private $websiteManager;
    private $user;

    /**
     * CreateVarnishLinkAction constructor.
     *
     * @param \Snowdog\DevTest\Model\UserManager    $userManager
     * @param \Snowdog\DevTest\Model\VarnishManager $varnishManager
     * @param \Snowdog\DevTest\Model\WebsiteManager $websiteManager
     */
    public function __construct(
        UserManager $userManager,
        VarnishManager $varnishManager,
        WebsiteManager $websiteManager
    ) {
        $this->varnishManager = $varnishManager;
        $this->websiteManager = $websiteManager;

        $this->user = $userManager->getLoggedUser();
    }

    /**
     * @return false|string
     */
    public function execute()
    {
        $error = false;

        if ($this->user) {
            /** @var string $data */
            $data = file_get_contents('php://input');
            /** @var array $decodedData */
            $decodedData = json_decode($data);
            $error = $this->processDecodedData($decodedData);
        }

        return json_encode(!$error);
    }

    /**
     * @param $websites
     * @param $varnishId
     *
     * @return bool
     */
    private function linkWebsitesToVarnishes($websites, $varnishId)
    {
        $error = false;
        foreach ($websites as $websiteId => $status) {
            $website = $this->websiteManager->getById($websiteId);

            if ($website && $website->getUserId() === $this->user->getUserId()) {
                try {
                    if ($status) {
                        $this->varnishManager->link($varnishId, $websiteId);
                    } else {
                        $this->varnishManager->unlink($varnishId, $websiteId);
                    }

                } catch (\Exception $e) {
                    $error = true;
                }
            }
        }

        return $error;
    }

    /**
     * @param $decodedData
     *
     * @return bool
     */
    private function processDecodedData($decodedData)
    {
        foreach ($decodedData as $varnishId => $websites) {
            /** @var Varnish $varnish */
            $varnish = $this->varnishManager->getById((int)$varnishId);
            if (!$varnish || $varnish->getUserId() !== $this->user->getUserId()) {
                continue;
            }
            $error = $this->linkWebsitesToVarnishes($websites, $varnishId);
        }

        return $error;
    }
}