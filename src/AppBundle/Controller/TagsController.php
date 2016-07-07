<?php

namespace AppBundle\Controller;

use AppBundle\Form\TagType;
use AppBundle\Repository\TagRepository;
use AppBundle\Entity\Tag;

use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\View as FOSView;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;

class TagsController extends BaseController
{
	/**
	 * Get all Tags entities.
	 *
	 * @Rest\Get("/tags", name="api_tags")
	 * @param ParamFetcherInterface $param_fetcher
	 *
	 * @Rest\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing notes.")
	 * @Rest\QueryParam(name="limit", requirements="\d+", default="10", description="How many notes to return.")
	 * @Rest\QueryParam(name="order_by", nullable=true, description="Order by fields. Must be an array ie. &order_by[name]=ASC&order_by[description]=DESC")
	 * @Rest\QueryParam(name="filters", nullable=true, description="Filter by fields. Must be an array ie. &filters[id]=3")
	 *
	 * @return Response
	 */
	public function getTagsAction(ParamFetcherInterface $param_fetcher)
	{
		try {
			$offset   = $param_fetcher->get('offset');
			$limit    = $param_fetcher->get('limit');
			$order_by = $param_fetcher->get('order_by');
			$filters  = !is_null($param_fetcher->get('filters')) ? $param_fetcher->get('filters') : [];

			$tags = $this->getRepo()->findBy($filters, $order_by, $limit, $offset);
			if ($tags) {
				return $this->getSerializeData($tags);
			}

			return FOSView::create('Not Found', Response::HTTP_NO_CONTENT);
		} catch (\Exception $e) {
			return FOSView::create($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
		}
	}

	/**
	 * Get a Tag entity
	 * @Rest\Get("/tags/show/{id}", name="api_tags_show")
	 *
	 * @return Response
	 */
	public function getAction(Tag $tag)
	{
		return $this->getSerializeData($tag);
	}

	/**
	 * Create a tag
	 *
	 * @Rest\Post("/tags/create", name="api_tags_create")
	 *
	 * @param Request $request
	 * @return Tag|FOSView|static
	 */
	public function createAction(Request $request)
	{
		$tag = new Tag();
		$form = $this->createForm(new TagType(), $tag, ["method" => $request->getMethod()]);
		$form->handleRequest($request);
		
		if ($form->isValid()) {

			$this->getRepo()->persist($tag);

			return $this->getSerializeData($tag);
		}

		return FOSView::create(['errors' => $form->getErrors()], Response::HTTP_INTERNAL_SERVER_ERROR);
	}

	/**
	 * Update a Tag entity.
	 *
	 * @Rest\Put("/tags/update/{id}", name="api_tags_update")
	 *
	 * @param Request $request
	 * @param $tag
	 *
	 * @return Response
	 */
	public function putAction(Request $request, Tag $tag)
	{
		try {
			$request->setMethod('PATCH');
			$form = $this->createForm(new TagType(), $tag, ["method" => $request->getMethod()]);
			$form->handleRequest($request);
			if ($form->isValid()) {
				$this->getDoctrine()->getManager()->flush($tag);

				return $this->getSerializeData($tag);
			}

			return FOSView::create(['errors' => $form->getErrors()], Response::HTTP_INTERNAL_SERVER_ERROR);
		} catch (\Exception $e) {
			return FOSView::create($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
		}
	}

	/**
	 * Delete a Tag entity.
	 *
	 * @Rest\Delete("/tags/delete/{id}", name="api_tags_delete")
	 *
	 * @param $tag
	 *
	 * @return Response
	 */
	public function deleteAction(Tag $tag)
	{
		try {
			$this->getRepo()->delete($tag);

			return FOSView::create(null, Response::HTTP_OK);
		} catch (\Exception $e) {
			return FOSView::create($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
		}
	}

	/**
	 * @return TagRepository
	 */
	public function getRepo()
	{
		return $this->getDoctrine()->getRepository('AppBundle:Tag');
	}
}