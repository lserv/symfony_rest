<?php
namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use FOS\UserBundle\Entity\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table("users")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User extends BaseUser
{
	/**
	 * @var integer
	 *
	 * @ORM\Column(name="id", type="integer")
	 * @ORM\Id
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	protected $id;

	/**
	 * Get id
	 *
	 * @return integer
	 */
	public function getId()
	{
		return $this->id;
	}

	/**
	 * @ORM\OneToMany(
	 *      targetEntity="Product",
	 *      mappedBy="user",
	 *      orphanRemoval=true
	 * )
	 */
	private $products;

	/**
	 * Constructor.
	 */
	public function __construct()
	{
		parent::__construct();
		$this->products = new ArrayCollection();
	}

	/**
	 * Add product.
	 *
	 * @param Product $product
	 *
	 * @return User
	 */
	public function addProduct(Product $product)
	{
		$this->products[] = $product;

		return $this;
	}

	/**
	 * Remove product.
	 *
	 * @param Product $product
	 */
	public function removeProducts(Product $product)
	{
		$this->products->removeElement($product);
	}

	/**
	 * Get products.
	 *
	 * @return \Doctrine\Common\Collections\Collection
	 */
	public function getProducts()
	{
		return $this->products;
	}
}