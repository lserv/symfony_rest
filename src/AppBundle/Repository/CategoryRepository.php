<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Category;
use Doctrine\ORM\EntityRepository;

/**
 * Class CategoryRepository
 * @package AppBundle\Repository
 */
class CategoryRepository extends EntityRepository 
{
	/**
	 * Stores Category in repository
	 *
	 * @param Category $category
	 */
	public function persist(Category $category) 
	{
		$this->getEntityManager()->persist($category);
		$this->getEntityManager()->flush();
	}

	/**
	 * @param $category
	 */
	public function delete(Category $category) 
	{
		$this->getEntityManager()->remove($category);
		$this->getEntityManager()->flush();
	}
}