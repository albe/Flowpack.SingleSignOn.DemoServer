<?php
namespace Flowpack\SingleSignOn\DemoServer\Controller;

/*                                                                                       *
 * This script belongs to the Flow Framework package "Flowpack.SingleSignOn.DemoServer". *
 *                                                                                       */

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Security\Authentication\Controller\AbstractAuthenticationController;

/**
 * Login controller for local authentication
 *
 * This controller will be called if no account was authenticated
 * on the SSO server.
 *
 * @Flow\Scope("singleton")
 */
class LoginController extends AbstractAuthenticationController {

    /**
     * Render a login form
     */
    public function indexAction() {
    }

    /**
     * Is called if authentication was successful
     *
     * @param \Neos\Flow\Mvc\ActionRequest $originalRequest The request that was intercepted by the security framework, NULL if there was none
     * @return string
     */
    protected function onAuthenticationSuccess(\Neos\Flow\Mvc\ActionRequest $originalRequest = null) {
        if ($originalRequest !== null) {
            $this->redirectToRequest($originalRequest);
        }

        $this->addFlashMessage('No original SSO request present. Account authenticated on server.', 'Authentication successful', \Neos\Error\Messages\Message::SEVERITY_OK);
        $this->redirect('index', 'Standard');
    }

    /**
     * Logout
     *
     * @return void
     */
    public function logoutAction() {
        parent::logoutAction();

        $this->addFlashMessage('You have been logged out');
        $this->redirect('index', 'Standard');
    }

}
