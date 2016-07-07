<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Tag;
use Doctrine\ORM\EntityRepository;

/**
 * Class TagRepository.
 */
class TagRepository extends EntityRepository
{
	/**
	 * Stores Tag in repository
	 *
	 * @param Tag $tag
	 */
	public function persist(Tag $tag)
	{
		$this->getEntityManager()->persist($tag);
		$this->getEntityManager()->flush();
	}

	/**
	 * @param $tag
	 */
	public function delete(Tag $tag)
	{
		$this->getEntityManager()->remove($tag);
		$this->getEntityManager()->flush();
	}
}
