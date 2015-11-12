<?php
namespace Flowpack\SingleSignOn\DemoServer\ViewHelpers\Format;

/*                                                                                       *
 * This script belongs to the Flow Framework package "Flowpack.SingleSignOn.DemoServer". *
 *                                                                                       */

use TYPO3\Flow\Annotations as Flow;

/**
 * Time ago format view helper
 */
class TimeAgoViewHelper extends \TYPO3\Fluid\Core\ViewHelper\AbstractViewHelper {

	/**
	 * Get the distance from the given time to now in words
	 *
	 * @param integer $timestamp
	 * @param integer $now The base time (defaults to now)
	 * @return string
	 */
	public function render($timestamp = NULL, $now = NULL) {
		if ($timestamp === NULL) {
			$timestamp = (integer)$this->renderChildren();
		}
		if ($now === NULL) {
			$now = time();
		}
		$inactivityInSeconds = $now - $timestamp;
		if ($inactivityInSeconds === 1) {
			$inactivityMessage = '1 second';
		} elseif ($inactivityInSeconds < 120) {
			$inactivityMessage = sprintf('%s seconds', $inactivityInSeconds);
		} elseif ($inactivityInSeconds < 3600) {
			$inactivityMessage = sprintf('%s minutes', intval($inactivityInSeconds / 60));
		} elseif ($inactivityInSeconds < 7200) {
			$inactivityMessage = 'more than an hour';
		} else {
			$inactivityMessage = sprintf('more than %s hours', intval($inactivityInSeconds / 3600));
		}
		return $inactivityMessage;
	}

}

