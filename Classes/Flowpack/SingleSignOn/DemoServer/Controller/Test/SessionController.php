<?php
namespace Flowpack\SingleSignOn\DemoServer\Controller\Test;

/*                                                                                       *
 * This script belongs to the Flow Framework package "Flowpack.SingleSignOn.DemoServer". *
 *                                                                                       */

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Mvc\Controller\ActionController;

/**
 * Session management for acceptance tests
 *
 * @Flow\Scope("singleton")
 */
class SessionController extends ActionController {

    /**
     * @Flow\Inject
     * @var \Neos\Flow\Session\SessionManagerInterface
     */
    protected $sessionManager;

    /**
     * @var string
     */
    protected $defaultViewObjectName = 'Neos\Flow\Mvc\View\JsonView';

    /**
     * @var array
     */
    protected $supportedMediaTypes = array('application/json');

    /**
     * Destroy all active sessions
     */
    public function destroyAllAction() {
        $sessions = $this->sessionManager->getActiveSessions();
        foreach ($sessions as $session) {
            $session->destroy('Through test service');
        }

        $this->view->assign('value', array('success' => true));
    }

}
