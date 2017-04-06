<?php
namespace Flowpack\SingleSignOn\DemoServer\Domain\Repository;

/*                                                                                       *
 * This script belongs to the Flow Framework package "Flowpack.SingleSignOn.DemoServer". *
 *                                                                                       */

use Neos\Flow\Annotations as Flow;
use Doctrine\ORM\Mapping as ORM;

use Neos\Flow\Persistence\Repository;

/**
 * User repository
 *
 * @Flow\Scope("singleton")
 */
class UserRepository extends Repository {

    /**
     * @Flow\Inject
     * @var \Neos\Flow\Security\AccountRepository
     */
    protected $accountRepository;

    /**
     * @param \Flowpack\SingleSignOn\DemoServer\Domain\Model\User $object
     */
    public function add($object) {
        $this->accountRepository->add($object->getPrimaryAccount());
        parent::add($object);
    }

    /**
     *
     * @param \Flowpack\SingleSignOn\DemoServer\Domain\Model\User $object
     */
    public function update($object) {
        $this->accountRepository->update($object->getPrimaryAccount());
        parent::update($object);
    }

    /**
     * @param \Flowpack\SingleSignOn\DemoServer\Domain\Model\User $object
     */
    public function remove($object) {
        $this->accountRepository->remove($object->getPrimaryAccount());
        parent::remove($object);
    }

}

