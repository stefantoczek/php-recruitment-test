<?php

namespace Snowdog\DevTest\Controller;

use Snowdog\DevTest\Model\UserManager;
use Snowdog\DevTest\Model\VarnishManager;

class CreateVarnishAction
{
    /**
     * @var VarnishManager
     */
    private $varnishManager;

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
            if ($this->varnishManager->create($user, $ip)) {
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