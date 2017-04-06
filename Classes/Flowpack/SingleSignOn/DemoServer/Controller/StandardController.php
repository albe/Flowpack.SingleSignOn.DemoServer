<?php
namespace Flowpack\SingleSignOn\DemoServer\Controller;

/*                                                                                       *
 * This script belongs to the Flow Framework package "Flowpack.SingleSignOn.DemoServer". *
 *                                                                                       */

use Neos\Flow\Annotations as Flow;
use Neos\Flow\Mvc\Controller\ActionController;

/**
 * SSO demo server standard controller
 *
 * @Flow\Scope("singleton")
 */
class StandardController extends ActionController {

    /**
     * Display a welcome screen
     *
     * @return void
     */
    public function indexAction() {
    }

}

