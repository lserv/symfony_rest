<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Product;
use AppBundle\Form\ProductType;
use AppBundle\Repository\ProductRepository;

use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\View as FOSView;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class ProductsController extends BaseController
{
	/**
	 * Get all Products entities.
	 *
	 * @Rest\Get("/products", name="api_products")
	 * @param ParamFetcherInterface $param_fetcher
	 *
	 * @Rest\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing notes.")
	 * @Rest\QueryParam(name="limit", requirements="\d+", default="10", description="How many notes to return.")
	 * @Rest\QueryParam(name="order_by", nullable=true, description="Order by fields. Must be an array ie. &order_by[name]=ASC&order_by[description]=DESC")
	 * @Rest\QueryParam(name="filters", nullable=true, description="Filter by fields. Must be an array ie. &filters[id]=3")
	 *
	 * @return Response
	 */
	public function getProductsAction(ParamFetcherInterface $param_fetcher)
	{
		try {
			$offset   = $param_fetcher->get('offset');
			$limit    = $param_fetcher->get('limit');
			$order_by = $param_fetcher->get('order_by');
			$filters  = !is_null($param_fetcher->get('filters')) ? $param_fetcher->get('filters') : [];

			$products = $this->getRepo()->findBy($filters, $order_by, $limit, $offset);
			if ($products) {
				return $this->getSerializeData($products, ['tags', 'user', 'category']);
			}

			return FOSView::create('Not Found', Response::HTTP_NO_CONTENT);
		} catch (\Exception $e) {
			return FOSView::create($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
		}
	}

	/**
	 * Get a Product entity
	 * @Rest\Get("/products/show/{id}", name="api_products_show")
	 *
	 * @return Response
	 */
	public function getAction(Product $product)
	{
		return $this->getSerializeData($product, ['tags', 'user', 'category']);
	}

	/**
	 * Create a product
	 *
	 * @Rest\Post("/products/create", name="api_products_create")
	 *
	 * @param Request $request
	 * @return Product|FOSView|static
	 */
	public function createAction(Request $request)
	{
		$product = new Product();
		$form = $this->createForm(new ProductType(), $product, ["method" => $request->getMethod()]);
		$form->handleRequest($request);
		
		if ($form->isValid()) {
			$category_id = ($request->request->getInt('category'))
				? $request->request->getInt('category')
				: 1;
			$category = $this->getDoctrine()
				->getRepository('AppBundle:Category')
				->find($category_id);

			$product->setCategory($category);
			$product->setUser($this->get('security.context')->getToken()->getUser());

			$this->getRepo()->persist($product);

			return $this->getSerializeData($product, ['tags', 'user', 'category']);
		}

		return FOSView::create(['errors' => $form->getErrors()], Response::HTTP_INTERNAL_SERVER_ERROR);
	}

	/**
	 * Update a Product entity.
	 *
	 * @Rest\Put("/products/update/{id}", name="api_products_update")
	 * @Security("product.isAuthor(user)")
	 *
	 * @param Request $request
	 * @param $product
	 *
	 * @return Response
	 */
	public function putAction(Request $request, Product $product)
	{
		try {
			$request->setMethod('PATCH');
			$form = $this->createForm(new ProductType(), $product, ["method" => $request->getMethod()]);
			$form->handleRequest($request);
			if ($form->isValid()) {
				$this->getDoctrine()->getManager()->flush($product);

				return $this->getSerializeData($product, ['tags', 'user', 'category']);
			}

			return FOSView::create(['errors' => $form->getErrors()], Response::HTTP_INTERNAL_SERVER_ERROR);
		} catch (\Exception $e) {
			return FOSView::create($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
		}
	}

	/**
	 * Delete a Category entity.
	 *
	 * @Rest\Delete("/products/delete/{id}", name="api_products_delete")
	 * @Security("product.isAuthor(user)")
	 *
	 * @param $product
	 *
	 * @return Response
	 */
	public function deleteAction(Product $product)
	{
		try {
			$this->getRepo()->delete($product);

			return FOSView::create(null, Response::HTTP_OK);
		} catch (\Exception $e) {
			return FOSView::create($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
		}
	}

	/**
	 * @return ProductRepository
	 */
	public function getRepo()
	{
		return $this->getDoctrine()->getRepository('AppBundle:Product');
	}
}