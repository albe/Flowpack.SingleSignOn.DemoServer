<?php
namespace Flowpack\SingleSignOn\DemoServer\Controller\Test;

/*                                                                                       *
 * This script belongs to the Flow Framework package "Flowpack.SingleSignOn.DemoServer". *
 *                                                                                       */

use Neos\Flow\Annotations as Flow;
use \Flowpack\SingleSignOn\DemoServer\Domain\Model\User;
use Neos\Flow\Mvc\Controller\ActionController;
use Neos\Flow\Property\TypeConverter\PersistentObjectConverter;

/**
 * User management for acceptance tests
 *
 * @Flow\Scope("singleton")
 */
class UserController extends ActionController {

    /**
     * @Flow\Inject
     * @var \Flowpack\SingleSignOn\DemoServer\Domain\Repository\UserRepository
     */
    protected $userRepository;

    /**
     * @Flow\Inject
     * @var \Neos\Flow\Security\AccountFactory
     */
    protected $accountFactory;

    /**
     * @Flow\Inject
     * @var \Neos\Flow\Security\Policy\PolicyService
     */
    protected $policyService;

    /**
     * @var string
     */
    protected $defaultViewObjectName = 'Neos\Flow\Mvc\View\JsonView';

    /**
     * @var array
     */
    protected $supportedMediaTypes = array('application/json');

    /**
     * Set property mapping configuration
     */
    public function initializeCreateAction() {
        $this->arguments->getArgument('user')->getPropertyMappingConfiguration()
            ->setTypeConverterOption('Neos\Flow\Property\TypeConverter\PersistentObjectConverter', PersistentObjectConverter::CONFIGURATION_CREATION_ALLOWED, true)
            ->allowAllProperties();
    }

    /**
     * @param \Flowpack\SingleSignOn\DemoServer\Domain\Model\User $user
     * @param string $username
     * @param string $password
     * @param string $role
     */
    public function createAction(User $user, $username, $password, $role) {
        $account = $this->accountFactory->createAccountWithPassword($username, $password);
        $user->addAccount($account);

        $user->setPassword($password);

        try {
            $roleObject = $this->policyService->getRole($role);
        } catch (\Neos\Flow\Security\Exception\NoSuchRoleException $e) {
            $roleObject = $this->policyService->createRole($role);
        }
        $user->getPrimaryAccount()->addRole($roleObject);
        $this->userRepository->add($user);

        $this->view->assign('value', array('success' => true));
    }

    /**
     * Reset test data
     */
    public function resetAction() {
        $users = $this->userRepository->findAll();
        foreach ($users as $user) {
            // FIXME Find another way to keep static setup data (e.g. source property)
            if ($user->getUsername() !== 'admin' && $user->getUsername() !== 'user1' && $user->getUsername() !== 'user2') {
                $this->userRepository->remove($user);
            }
        }
        $this->persistenceManager->persistAll();

        $this->view->assign('value', array('success' => true));
    }

}
