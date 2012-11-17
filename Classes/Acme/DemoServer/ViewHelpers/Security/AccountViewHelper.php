<?php
namespace Acme\DemoServer\ViewHelpers\Security;

/*                                                                        *
 * This script belongs to the TYPO3 Flow package "Acme.DemoServer".       *
 *                                                                        *
 *                                                                        */

use TYPO3\Flow\Annotations as Flow;

/**
 * Security account view helper
 */
class AccountViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * @Flow\Inject
	 * @var \TYPO3\Flow\Security\Context
	 */
	protected $securityContext;

	/**
	 * Assign the authenticated account to a template variable
	 *
	 * @param string $as Variable name for the account
	 * @return mixed
	 */
	public function render($as) {
		$this->templateVariableContainer->add($as, $this->securityContext->getAccount());
		$result = $this->renderChildren();
		$this->templateVariableContainer->remove($as);
		return $result;
	}

}

?>