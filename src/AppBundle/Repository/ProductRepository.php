<?php

namespace AppBundle\Repository;

use AppBundle\Entity\Product;
use Doctrine\ORM\EntityRepository;

/**
 * Class ProductRepository
 * @package AppBundle\Repository
 */
class ProductRepository extends EntityRepository
{
	/**
	 * Stores Product in repository
	 *
	 * @param Product $product
	 */
	public function persist(Product $product)
	{
		$this->getEntityManager()->persist($product);
		$this->getEntityManager()->flush();
	}

	/**
	 * @param $product
	 */
	public function delete(Product $product)
	{
		$this->getEntityManager()->remove($product);
		$this->getEntityManager()->flush();
	}
}
