<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use AppBundle\Repository\UserRepository;

use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\View as FOSView;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use FOS\RestBundle\Controller\Annotations as Rest;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class UsersController extends BaseController
{
	/**
	 * Get all Users entities.
	 *
	 * @Rest\Get("/users", name="api_users")
	 * @param ParamFetcherInterface $param_fetcher
	 *
	 * @Rest\QueryParam(name="offset", requirements="\d+", nullable=true, description="Offset from which to start listing notes.")
	 * @Rest\QueryParam(name="limit", requirements="\d+", default="10", description="How many notes to return.")
	 * @Rest\QueryParam(name="order_by", nullable=true, description="Order by fields. Must be an array ie. &order_by[name]=ASC&order_by[description]=DESC")
	 * @Rest\QueryParam(name="filters", nullable=true, description="Filter by fields. Must be an array ie. &filters[id]=3")
	 *
	 * @return Response
	 */
	public function getUsersAction(ParamFetcherInterface $param_fetcher)
	{
		try {
			$offset   = $param_fetcher->get('offset');
			$limit    = $param_fetcher->get('limit');
			$order_by = $param_fetcher->get('order_by');
			$filters  = !is_null($param_fetcher->get('filters')) ? $param_fetcher->get('filters') : [];

			$users = $this->getRepo()->findBy($filters, $order_by, $limit, $offset);
			if ($users) {
				return $this->getSerializeData($users, ['products']);
			}

			return FOSView::create('Not Found', Response::HTTP_NO_CONTENT);
		} catch (\Exception $e) {
			return FOSView::create($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
		}
	}

	/**
	 * Get a User entity
	 * @Rest\Get("/users/show/{id}", name="api_users_show")
	 *
	 * @return Response
	 */
	public function getAction(User $user)
	{
		return $this->getSerializeData($user, ['products']);
	}

	/**
	 * Create a user
	 *
	 * @Rest\Post("/users/create", name="api_users_create")
	 * @Security("has_role('ROLE_ADMIN')")
	 *
	 * @param Request $request
	 * @return User|FOSView|static
	 */
	public function createAction(Request $request)
	{
		$user = new User();
		$form = $this->createForm(new UserType(), $user, ["method" => $request->getMethod()]);
		$form->handleRequest($request);
		
		if ($form->isValid()) {
			$encodedPassword = $this
				->get('security.password_encoder')
				->encodePassword($user, $request->request->get('password'));
			$user->setPassword($encodedPassword);

			$this->getRepo()->persist($user);

			return $this->getSerializeData($user, ['products']);
		}

		return FOSView::create(['errors' => $form->getErrors()], Response::HTTP_INTERNAL_SERVER_ERROR);
	}

	/**
	 * Update a User entity.
	 *
	 * @Rest\Put("/users/update/{id}", name="api_users_update")
	 * @Security("has_role('ROLE_ADMIN')")
	 *
	 * @param Request $request
	 * @param $user
	 *
	 * @return Response
	 */
	public function putAction(Request $request, User $user)
	{
		try {
			$request->setMethod('PATCH');
			$form = $this->createForm(new UserType(), $user, ["method" => $request->getMethod()]);
			$form->handleRequest($request);
			if ($form->isValid()) {
				$this->getDoctrine()->getManager()->flush($user);

				return $this->getSerializeData($user, ['products']);
			}

			return FOSView::create(['errors' => $form->getErrors()], Response::HTTP_INTERNAL_SERVER_ERROR);
		} catch (\Exception $e) {
			return FOSView::create($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
		}
	}

	/**
	 * Delete a User entity.
	 *
	 * @Rest\Delete("/users/delete/{id}", name="api_users_delete")
	 * @Security("has_role('ROLE_ADMIN')")
	 *
	 * @param $user
	 *
	 * @return Response
	 */
	public function deleteAction(User $user)
	{
		try {
			$this->getRepo()->delete($user);

			return FOSView::create(null, Response::HTTP_OK);
		} catch (\Exception $e) {
			return FOSView::create($e->getMessage(), Response::HTTP_INTERNAL_SERVER_ERROR);
		}
	}

	/**
	 * @return UserRepository
	 */
	public function getRepo()
	{
		return $this->getDoctrine()->getRepository('AppBundle:User');
	}
}