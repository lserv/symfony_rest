<?php

namespace AppBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DemoController extends FOSRestController
{
	/**
	 * @Route("/", name="homepage")
	 */
	public function getDemosAction()
	{
		$view = $this->view([
			'version'      => '1',
			'Project Name' => 'RestApi'
		]);

		return $this->handleView($view);
	}
}
