<?php
namespace Acme\DemoServer\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Acme.DemoServer".       *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * SSO server configuration controller
 *
 * @Flow\Scope("singleton")
 */
class ConfigurationController extends \TYPO3\Flow\Mvc\Controller\ActionController {

	/**
	 * @Flow\Inject
	 * @var \TYPO3\SingleSignOn\Server\Domain\Repository\SsoClientRepository
	 */
	protected $ssoClientRepository;

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Configuration\ConfigurationManager
	 */
	protected $configurationManager;

	/**
	 * Display configuration and registered clients
	 */
	public function indexAction() {
		$ssoClients = $this->ssoClientRepository->findAll();
		$this->view->assign('ssoClients', $ssoClients);

		$serverSettings = $this->configurationManager->getConfiguration(\TYPO3\Flow\Configuration\ConfigurationManager::CONFIGURATION_TYPE_SETTINGS, 'TYPO3.SingleSignOn.Server');
		$yaml = \Symfony\Component\Yaml\Yaml::dump($serverSettings);
		$this->view->assign('serverSettings', $yaml);
	}

}

?>