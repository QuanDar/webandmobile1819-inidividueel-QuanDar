<?php
/**
 * Created by PhpStorm.
 * User: QuanDar
 * Date: 08/11/2018
 * Time: 14:45
 */

namespace App\Form;
use App\Entity\Message;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UpdateMessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', HiddenType::class, array('label' => false))
            ->add('content', TextType::class, array('required' => false, 'label' => true))
            ->add('date', DateType::class, array('required' => false, 'label' => true,  'by_reference' => true))
            ->add('upVotes', NumberType::class, array('required' => false, 'label' => true))
            ->add('downVotes', NumberType::class, array('required' => false, 'label' => true))
            ->add('user', CommentUserType::class, array('required' => false, 'label' => false));
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Message::class,
        ]);
    }
}
