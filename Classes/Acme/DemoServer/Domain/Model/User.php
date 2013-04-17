<?php
namespace Acme\DemoServer\Domain\Model;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Acme.DemoServer".       *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

/**
 * User domain model
 *
 * @Flow\Entity
 */
class User extends \TYPO3\Party\Domain\Model\AbstractParty {

	/**
	 * The username of the user
	 * @var string
	 * @Flow\Validate(type="NotEmpty")
	 */
	protected $username;

	/**
	 * @var string
	 */
	protected $firstname = '';

	/**
	 * @var string
	 */
	protected $lastname = '';

	/**
	 * @var string
	 */
	protected $company = '';

	/**
	 * @var string
	 * @Flow\Validate(type="NotEmpty")
	 * @Flow\Validate(type="RegularExpression", options={"regularExpression"="/^(Acme\.DemoInstance\:Administrator|Acme\.DemoInstance\:User)$/"})
	 */
	protected $role;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Security\Cryptography\HashService
	 */
	protected $hashService;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Security\Policy\RoleRepository
	 */
	protected $roleRepository;

	/**
	 * Construct a user
	 */
	public function __construct() {
		parent::__construct();
		$account = new \TYPO3\Flow\Security\Account();
		$account->setAuthenticationProviderName('DefaultProvider');
		$this->addAccount($account);
	}

	/**
	 * @param string $username
	 */
	public function setUsername($username) {
		$this->username = $username;
		$account = $this->getPrimaryAccount();
		$account->setAccountIdentifier($username);
	}

	/**
	 * @return string
	 */
	public function getUsername() {
		return $this->username;
	}

	/**
	 * @param string $company
	 */
	public function setCompany($company) {
		$this->company = $company;
	}

	/**
	 * @return string
	 */
	public function getRole() {
		return $this->role;
	}

	/**
	 * @param string $role
	 */
	public function setRole($role) {
		if (is_string($role)) {
			$roleIdentifier = $role;
			$role = $this->roleRepository->findByIdentifier($roleIdentifier);
			if ($role === NULL) {
				throw new \InvalidArgumentException('The role "' . $roleIdentifier . '" does not exist.', 1366148156);
			}
		}
		$this->role = $role->getIdentifier();
		$account = $this->getPrimaryAccount();
		$account->setRoles(array($role));
	}

	/**
	 * @return string
	 */
	public function getCompany() {
		return $this->company;
	}

	/**
	 * @param string $firstname
	 */
	public function setFirstname($firstname) {
		$this->firstname = $firstname;
	}

	/**
	 * @return string
	 */
	public function getFirstname() {
		return $this->firstname;
	}

	/**
	 * @param string $lastname
	 */
	public function setLastname($lastname) {
		$this->lastname = $lastname;
	}

	/**
	 * @return string
	 */
	public function getLastname() {
		return $this->lastname;
	}

	/**
	 * @param string $password
	 */
	public function setPassword($password) {
		$account = $this->getPrimaryAccount();
		$account->setCredentialsSource($this->hashService->hashPassword($password));
	}

	/**
	 * @return \TYPO3\Flow\Security\Account
	 */
	public function getPrimaryAccount() {
		if (count($this->accounts) > 0) {
			return $this->accounts->first();
		}
		return NULL;
	}

}

?>