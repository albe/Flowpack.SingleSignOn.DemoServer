<?php
namespace Acme\DemoServer\Controller;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Acme.DemoServer".       *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * SSO server standard controller
 *
 * @Flow\Scope("singleton")
 */
class StandardController extends \TYPO3\Flow\Mvc\Controller\ActionController {

	/**
	 * Display a welcome screen
	 *
	 * @return void
	 */
	public function indexAction() {
	}

}

?>