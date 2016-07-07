<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Symfony\Component\Form\Form;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\GetSetMethodNormalizer;

class BaseController extends FOSRestController
{
	/**
	 * Create a form without a name
	 *
	 * @param null $type
	 * @param null $data
	 * @param array $options
	 *
	 * @return Form|FormInterface
	 */
	public function createForm($type = null, $data = null, array $options = array())
	{
		$form = $this->container->get('form.factory')->createNamed(
			null, //since we're not including the form name in the request, set this to null
			$type,
			$data,
			$options
		);

		return $form;
	}

	/**
	 * Return serialize $object
	 *
	 * @param $object
	 * @param array $ignored_attributes
	 * 
	 * @return Response
	 */
	protected function getSerializeData($object, array $ignored_attributes = [])
	{
		//$object = $this->get('serializer')->serialize($data, 'json');

		$normalizer = new GetSetMethodNormalizer();
		if ($ignored_attributes) {
			$normalizer->setIgnoredAttributes($ignored_attributes);
		}

		$serializer = new Serializer([$normalizer], [new JsonEncoder()]);

		return $this->handleView($this->view($serializer->serialize($object, 'json'), Response::HTTP_OK));
	}
}
