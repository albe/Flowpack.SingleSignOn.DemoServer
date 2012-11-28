<?php
namespace Acme\DemoServer\Controller\Test;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Acme.DemoServer".       *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use \Acme\DemoServer\Domain\Model\User;

/**
 * User management for acceptance tests
 *
 * @Flow\Scope("singleton")
 */
class UserController extends \TYPO3\Flow\Mvc\Controller\ActionController {

	/**
	 * @Flow\Inject
	 * @var \Acme\DemoServer\Domain\Repository\UserRepository
	 */
	protected $userRepository;

	/**
	 * @var string
	 */
	protected $defaultViewObjectName = 'TYPO3\Flow\Mvc\View\JsonView';

	/**
	 * @var array
	 */
	protected $supportedMediaTypes = array('application/json');

	/**
	 * Set property mapping configuration
	 */
	public function initializeCreateAction() {
		$this->arguments->getArgument('user')->getPropertyMappingConfiguration()
			->setTypeConverterOption('TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter', \TYPO3\Flow\Property\TypeConverter\PersistentObjectConverter::CONFIGURATION_CREATION_ALLOWED, TRUE)
			->allowAllProperties();
	}

	/**
	 * @param \Acme\DemoServer\Domain\Model\User $user
	 * @param string $password
	 */
	public function createAction(User $user, $password) {
		$user->setPassword($password);
		$this->userRepository->add($user);

		$this->view->assign('value', array('success' => TRUE));
	}

	/**
	 * Reset test data
	 */
	public function resetAction() {
		$users = $this->userRepository->findAll();
		foreach ($users as $user) {
			if ($user->getUsername() !== 'admin') {
				$this->userRepository->remove($user);
			}
		}
		$this->persistenceManager->persistAll();

		$this->view->assign('value', array('success' => TRUE));
	}

}
?>