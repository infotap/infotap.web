<?php

namespace Infotap\AdminBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('aadharId')
            ->add('name')
            ->add('gender','choice',array('attr'=>array('class'=>'profile-input-radio'),'empty_value' => false,'required'=>false,'expanded'=>true,'multiple'=>false,'choices'=>array(1=>'Male',0=>'Female')))
            ->add('dob')
            ->add('email')
            ->add('mobile')
            ->add('status','choice',array('attr'=>array('class'=>'profile-input-radio'),'empty_value' => false,'required'=>false,'expanded'=>true,'multiple'=>false,'choices'=>array(1=>'Active',0=>'Inactive')))
            ->add('otpCode')
            ->add('location')
            ->add('area')
            ->add('city')
            ->add('state')
            ->add('pincode')
            ->add('latitude')
            ->add('longitude')
            ->add('androidRegId')
            ->add('accessToken')
        ;
    }
    
    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Infotap\AdminBundle\Entity\User'
        ));
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'infotap_adminbundle_user';
    }
}
