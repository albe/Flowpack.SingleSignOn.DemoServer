<?php
namespace Acme\DemoServer\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Acme.DemoServer".       *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * SSO server sessions controller
 *
 * @Flow\Scope("singleton")
 */
class SessionsController extends \TYPO3\Flow\Mvc\Controller\ActionController {

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Session\SessionManagerInterface
	 */
	protected $sessionManager;

	/**
	 * Display sessions
	 */
	public function indexAction() {
		$currentSession = $this->sessionManager->getCurrentSession();
		$this->view->assign('currentSession', $currentSession);

		$activeSessions = $this->sessionManager->getActiveSessions();
		$this->view->assign('activeSessions', $activeSessions);
	}

	/**
	 * Destroy a session
	 *
	 * @param \TYPO3\Flow\Session\Session $session
	 * @Flow\IgnoreValidation("$session")
	 */
	public function destroyAction(\TYPO3\Flow\Session\Session $session) {
		$session->destroy('Through web interface');

		$this->addFlashMessage('Session destroyed');

		$this->redirect('index');
	}

	/**
	 * Destroys all sessions
	 */
	public function destroyAllAction() {
		$sessions = $this->sessionManager->getActiveSessions();
		foreach ($sessions as $session) {
			$session->destroy('Through test service');
		}

		$this->addFlashMessage('All sessions destroyed');

		$this->redirect('index');
	}

}

?>