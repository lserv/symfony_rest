<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
	    $is_new = true;
	    if (!empty($options['data']) && !is_null($options['data']->getId())) {
		    $is_new = false;
	    }

	    $builder
		    ->add('username', 'text', ['required' => $is_new])
		    ->add('email', 'email', ['required' => $is_new])
		    ->add('password', 'password', ['required' => $is_new]);
    }

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'data_class' => 'AppBundle\Entity\User',
			'csrf_protection' => false,
		]);
	}

    public function getName()
    {
        return 'user';
    }
}
