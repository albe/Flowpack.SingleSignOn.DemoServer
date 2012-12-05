<?php
namespace Acme\DemoServer\Controller\Test;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Acme.DemoServer".       *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use \Acme\DemoServer\Domain\Model\User;

/**
 * Session management for acceptance tests
 *
 * @Flow\Scope("singleton")
 */
class SessionController extends \TYPO3\Flow\Mvc\Controller\ActionController {

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Session\SessionManagerInterface
	 */
	protected $sessionManager;

	/**
	 * @var string
	 */
	protected $defaultViewObjectName = 'TYPO3\Flow\Mvc\View\JsonView';

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

		$this->view->assign('value', array('success' => TRUE));
	}

}
?>