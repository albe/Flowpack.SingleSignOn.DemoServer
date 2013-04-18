<?php
namespace Acme\DemoServer\Command;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Acme.DemoServer".       *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * Setup command controller
 *
 * @Flow\Scope("singleton")
 */
class SetupCommandController extends \TYPO3\Flow\Cli\CommandController {

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Security\Cryptography\RsaWalletServiceInterface
	 */
	protected $rsaWalletService;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Configuration\Source\YamlSource
	 */
	protected $yamlSource;

	/**
	 * @Flow\Inject
	 * @var \Flowpack\SingleSignOn\Server\Domain\Repository\SsoClientRepository
	 */
	protected $ssoClientRepository;

	/**
	 * @Flow\Inject
	 * @var \Flowpack\SingleSignOn\Server\Domain\Repository\AccessTokenRepository
	 */
	protected $accessTokenRepository;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Security\AccountRepository
	 */
	protected $accountRepository;

	/**
	 * @Flow\Inject
	 * @var \Acme\DemoServer\Domain\Repository\UserRepository
	 */
	protected $userRepository;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Security\Policy\RoleRepository
	 */
	protected $roleRepository;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Security\AccountFactory
	 */
	protected $accountFactory;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Persistence\PersistenceManagerInterface
	 */
	protected $persistenceManager;

	/**
	 * @var array
	 */
	protected $settings;

	/**
	 * Sets up a demo server installation with fixture data
	 *
	 * Overwrites existing data in the database.
	 *
	 * DO NOT USE IT FOR PRODUCTION!
	 */
	public function setupCommand() {
		$privateKeyString = \TYPO3\Flow\Utility\Files::getFileContents('resource://Acme.DemoServer/Private/Fixtures/DemoServer.key', FILE_TEXT);
		if ($privateKeyString === FALSE) {
			$this->outputLine('Could not open DemoServer.key, aborting.');
			return;
		}
		$serverKeyPairUuid = $this->rsaWalletService->registerKeyPairFromPrivateKeyString($privateKeyString);
		$this->outputLine('Registered demo server key pair');

		$globalSettings = $this->yamlSource->load(FLOW_PATH_CONFIGURATION . '/Settings');
		$globalSettings['TYPO3']['SingleSignOn']['Server']['server']['keyPairUuid'] = $serverKeyPairUuid;
		$this->yamlSource->save(FLOW_PATH_CONFIGURATION . '/Settings', $globalSettings);
		$this->outputLine('Updated settings');

		$this->accessTokenRepository->removeAll();
		$this->ssoClientRepository->removeAll();
		$this->accountRepository->removeAll();
		$this->userRepository->removeAll();
			// Persist removal, because otherwise primary key constraints fail
		$this->persistenceManager->persistAll();

		if (!isset($this->settings['clients']) || !is_array($this->settings['clients'])) {
			$this->outputLine('Missing Acme.DemoServer.clients settings, aborting.');
			return;
		}
		foreach ($this->settings['clients'] as $clientConfiguration) {
			$ssoClient = new \Flowpack\SingleSignOn\Server\Domain\Model\SsoClient();
			$ssoClient->setServiceBaseUri($clientConfiguration['serviceBaseUri']);

			if (isset($clientConfiguration['publicKeyFilename'])) {
				$clientPublicKeyFilename = $clientConfiguration['publicKeyFilename'];
			} else {
				$clientPublicKeyFilename = 'resource://Acme.DemoServer/Private/Fixtures/DemoClient.pub';
			}
			$clientPublicKeyString = \TYPO3\Flow\Utility\Files::getFileContents($clientPublicKeyFilename, FILE_TEXT);
			if ($clientPublicKeyString === FALSE) {
				$this->outputLine('Could not open DemoClient.pub, aborting.');
				return;
			}
			$clientPublicKeyUuid = $this->rsaWalletService->registerPublicKeyFromString($clientPublicKeyString);

			$ssoClient->setPublicKey($clientPublicKeyUuid);
			$this->ssoClientRepository->add($ssoClient);
			$this->outputLine('Created demo client "' . $ssoClient->getServiceBaseUri() . '"');
		}

		$this->addUserCommand('admin', 'password', 'Acme.DemoInstance:Administrator', 'Joe', 'Bloggs');
		$this->addUserCommand('user1', 'password', 'Acme.DemoInstance:User', 'Michael', 'Potter');
		$this->addUserCommand('user2', 'password', 'Acme.DemoInstance:User', 'Jamie', 'Morgan');
	}

	/**
	 * Add a user and account with DefaultProvider
	 *
	 * @param string $username
	 * @param string $password
	 * @param string $roles
	 * @param string $firstname
	 * @param string $lastname
	 * @return void
	 */
	public function addUserCommand($username, $password, $roles, $firstname = '', $lastname = '') {
		$roleIdentifiers = \TYPO3\Flow\Utility\Arrays::trimExplode(',', $roles);
		$user = new \Acme\DemoServer\Domain\Model\User();
		$user->setUsername($username);
		$user->setPassword($password);
		$user->setFirstname($firstname);
		$user->setLastname($lastname);
		$role = $this->roleRepository->findByIdentifier($roleIdentifiers[0]);
		if ($role === NULL) {
			$role = new \TYPO3\Flow\Security\Policy\Role($roleIdentifiers[0]);
			$this->roleRepository->add($role);
		}
		$user->setRole($role);
		$this->userRepository->add($user);
		$this->outputLine('Created user and account with identifier "' . $username . '"');
	}

	/**
	 * @param array $settings
	 */
	public function injectSettings($settings) {
		$this->settings = $settings;
	}

}
?>