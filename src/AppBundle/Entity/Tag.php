<?php

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="tags")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\TagRepository")
 */
class Tag
{
    /**
     * Tag id.
     *
     * @var int
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * Tag text.
     *
     * @var string
     * @ORM\Column(name="title", type="string", length=255)
     */
    private $title = '';

    /**
     * @ORM\ManyToMany(targetEntity="Product", mappedBy="tags")
     */
    private $products;

    /**
     * @param null|string $title A tag title
     */
    public function __construct($title = null)
    {
        $this->title = $title;
        $this->products = new ArrayCollection();
    }

    /**
     * Get Tag id.
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set Tag text.
     *
     * @param $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }

    /**
     * Get Tag text.
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * This method allows a class to decide how it will react when it is treated like a string.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getTitle();
    }

	/**
	 * Get products for this tag.
	 *
	 * @return ArrayCollection
	 */
	public function getProducts()
	{
		return $this->products;
	}
	
    /**
     * Add product.
     *
     * @param Product $product
     *
     * @return Tag
     */
    public function addProducts(Product $product)
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
}
