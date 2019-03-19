<?php

namespace Snowdog\DevTest\Controller;

use Snowdog\DevTest\Model\UserManager;
use Snowdog\DevTest\Model\Varnish;
use Snowdog\DevTest\Model\VarnishManager;
use Snowdog\DevTest\Model\Website;
use Snowdog\DevTest\Model\WebsiteManager;

class CreateVarnishLinkAction
{
    /**
     * @var UserManager
     */
    private $userManager;
    /**
     * @var VarnishManager
     */
    private $varnishManager;
    /** @var WebsiteManager */
    private $websiteManager;

    public function __construct(
        UserManager $userManager,
        VarnishManager $varnishManager,
        WebsiteManager $websiteManager
    ) {
        $this->userManager = $userManager;
        $this->varnishManager = $varnishManager;
        $this->websiteManager = $websiteManager;

        $this->user = $this->userManager->getLoggedUser();
    }

    public function execute()
    {
        if (!$this->user) {
            return;
        }

        $data = file_get_contents('php://input');
        $decodedData = json_decode($data);
        $error = false;
        foreach ($decodedData as $varnishId => $websites) {
            /** @var Varnish $varnish */
            $varnish = $this->varnishManager->getById((int)$varnishId);
            if (!$varnish || $varnish->getUserId() !== $this->user->getUserId()) {
                return;
            }
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
        }

        return json_encode(!$error);
    }
}