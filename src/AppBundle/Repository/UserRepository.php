<?php

namespace AppBundle\Repository;

use Doctrine\ORM\EntityRepository;
use AppBundle\Entity\User;

class UserRepository extends EntityRepository
{
	/**
	 * Stores User in repository
	 *
	 * @param User $user
	 */
	public function persist(User $user)
	{
		$this->getEntityManager()->persist($user);
		$this->getEntityManager()->flush();
	}
	
	/**
	 * @param $user
	 */
	public function delete(User $user)
	{
		$this->getEntityManager()->remove($user);
		$this->getEntityManager()->flush();
	}
	
    /**
     * @param $username
     * @return User
     */
    public function findUserByUsername($username)
    {
        return $this->findOneBy([
            'username' => $username
        ]);
    }

    /**
     * @param $email
     * @return User
     */
    public function findUserByEmail($email)
    {
        return $this->findOneBy([
            'email' => $email
        ]);
    }
}
