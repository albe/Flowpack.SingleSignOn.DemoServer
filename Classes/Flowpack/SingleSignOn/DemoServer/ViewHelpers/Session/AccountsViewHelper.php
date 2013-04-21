<?php
namespace Flowpack\SingleSignOn\DemoServer\ViewHelpers\Session;

/*                                                                                   *
 * This script belongs to the TYPO3 Flow package "Flowpack.SingleSignOn.DemoServer". *
 *                                                                                   */

use TYPO3\Flow\Annotations as Flow;

/**
 * Get registered accounts from a session
 */
class AccountsViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * Assign the registered accounts to a template variable
	 *
	 * @param \TYPO3\Flow\Session\SessionInterface $session
	 * @param string $as Variable name for the account
	 * @return mixed
	 */
	public function render($session, $as) {
		$data = $session->getData('TYPO3_Flow_Security_Accounts');
		if ($data !== FALSE) {
			$accounts = $data;
		} else {
			$accounts = array();
		}
		$this->templateVariableContainer->add($as, $accounts);
		$result = $this->renderChildren();
		$this->templateVariableContainer->remove($as);
		return $result;
	}

}

?>