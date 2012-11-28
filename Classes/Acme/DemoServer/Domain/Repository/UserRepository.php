<?php
namespace Acme\DemoServer\Domain\Repository;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Acme.DemoServer".       *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

use \TYPO3\Flow\Persistence\QueryInterface;

/**
 * User repository
 *
 * @Flow\Scope("singleton")
 */
class UserRepository extends \TYPO3\Flow\Persistence\Repository {

	/**
	 * @var array
	 */
	protected $defaultOrderings = array('username' => QueryInterface::ORDER_ASCENDING);

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Security\AccountRepository
	 */
	protected $accountRepository;

	/**
	 * @param \Acme\DemoServer\Domain\Model\User $object
	 */
	public function add($object) {
		$this->accountRepository->add($object->getPrimaryAccount());
		parent::add($object);
	}

	/**
	 *
	 * @param \Acme\DemoServer\Domain\Model\User $object
	 */
	public function update($object) {
		$this->accountRepository->update($object->getPrimaryAccount());
		parent::update($object);
	}

	/**
	 * @param \Acme\DemoServer\Domain\Model\User $object
	 */
	public function remove($object) {
		$this->accountRepository->remove($object->getPrimaryAccount());
		parent::remove($object);
	}

}

?>