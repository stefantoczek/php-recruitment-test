<?php

namespace Snowdog\DevTest\Controller;

use Snowdog\DevTest\Model\UserManager;
use Snowdog\DevTest\Model\VarnishManager;

/**
 * Class CreateVarnishAction
 *
 * @package Snowdog\DevTest\Controller
 */
class CreateVarnishAction
{

    /**
     * @var VarnishManager
     */
    private $varnishManager;
    private $userManager;

    /**
     * CreateVarnishAction constructor.
     *
     * @param \Snowdog\DevTest\Model\UserManager    $userManager
     * @param \Snowdog\DevTest\Model\VarnishManager $varnishManager
     */
    public function __construct(UserManager $userManager, VarnishManager $varnishManager)
    {
        $this->userManager = $userManager;
        $this->varnishManager = $varnishManager;
    }

    public function execute()
    {

        $ip = $_POST['ip'];
        $user = $this->userManager->getLoggedUser();
        if ($user) {
            if (!$this->varnishManager->validateIPaddress($ip)) {
                $_SESSION['flash'] = 'Error while validating ip address!';
            } elseif ($this->varnishManager->create($user, $ip)) {
                $_SESSION['flash'] = 'Varnish server successfully added!';
            } else {
                $_SESSION['flash'] = 'Error while adding varnish server, try again!';
            }
        } else {
            $_SESSION['flash'] = 'You need to be logged in in order to add varnish';
        }
        header('Location: /varnish');
    }
}