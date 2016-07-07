<?php

namespace AppBundle\Controller;

use AppBundle\Form\CategoryType;
use AppBundle\Repository\CategoryRepository;
use AppBundle\Entity\Category;

use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\View as FOSView;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;

class CategoriesController extends BaseController
{
	/**
	 * Get all Categories entities.
	 *
	 * @Rest\Get("/categories", name="api_categories")
	 * @param ParamFetcherInterface $param_fetcher
	 *
	 * @Rest\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing notes.")
	 * @Rest\QueryParam(name="limit", requirements="\d+", default="10", description="How many notes to return.")
	 * @Rest\QueryParam(name="order_by", nullable=true, description="Order by fields. Must be an array ie. &order_by[name]=ASC&order_by[description]=DESC")
	 * @Rest\QueryParam(name="filters", nullable=true, description="Filter by fields. Must be an array ie. &filters[id]=3")
	 *
	 * @return Response
	 */
	public function getCategoriesAction(ParamFetcherInterface $param_fetcher)
	{
		try {
			$offset   = $param_fetcher->get('offset');
			$limit    = $param_fetcher->get('limit');
			$order_by = $param_fetcher->get('order_by');
			$filters  = !is_null($param_fetcher->get('filters')) ? $param_fetcher->get('filters') : [];

			$categories = $this->getRepo()->findBy($filters, $order_by, $limit, $offset);
			if ($categories) {
				return $this->getSerializeData($categories, ['product']);
			}

			return FOSView::create('Not Found', Response::HTTP_NO_CONTENT);
		} catch (\Exception $e) {
			return FOSView::create($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
		}
	}

	/**
	 * Get a Category entity
	 * @Rest\Get("/categories/show/{id}", name="api_categories_show")
	 *
	 * @return Response
	 */
	public function getAction(Category $category)
	{
		return $this->getSerializeData($category, ['product']);
	}

	/**
	 * Create a category
	 *
	 * @Rest\Post("/categories/create", name="api_categories_create")
	 *
	 * @param Request $request
	 * @return Category|FOSView|static
	 */
	public function createAction(Request $request)
	{
		$category = new Category();
		$form = $this->createForm(new CategoryType(), $category, ["method" => $request->getMethod()]);
		$form->handleRequest($request);
		
		if ($form->isValid()) {

			$this->getRepo()->persist($category);

			return $this->getSerializeData($category, ['product']);
		}

		return FOSView::create(['errors' => $form->getErrors()], Response::HTTP_INTERNAL_SERVER_ERROR);
	}

	/**
	 * Update a Categories entity.
	 *
	 * @Rest\Put("/categories/update/{id}", name="api_categories_update")
	 *
	 * @param Request $request
	 * @param $category
	 *
	 * @return Response
	 */
	public function putAction(Request $request, Category $category)
	{
		try {
			$request->setMethod('PATCH');
			$form = $this->createForm(new CategoryType(), $category, ["method" => $request->getMethod()]);
			$form->handleRequest($request);
			if ($form->isValid()) {
				$this->getDoctrine()->getManager()->flush($category);

				return $this->getSerializeData($category, ['product']);
			}

			return FOSView::create(['errors' => $form->getErrors()], Response::HTTP_INTERNAL_SERVER_ERROR);
		} catch (\Exception $e) {
			return FOSView::create($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
		}
	}

	/**
	 * Delete a Category entity.
	 *
	 * @Rest\Delete("/categories/delete/{id}", name="api_categories_delete")
	 *
	 * @param $category
	 *
	 * @return Response
	 */
	public function deleteAction(Category $category)
	{
		try {
			$this->getRepo()->delete($category);

			return FOSView::create(null, Response::HTTP_OK);
		} catch (\Exception $e) {
			return FOSView::create($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
		}
	}

	/**
	 * @return CategoryRepository
	 */
	public function getRepo()
	{
		return $this->getDoctrine()->getRepository('AppBundle:Category');
	}
}