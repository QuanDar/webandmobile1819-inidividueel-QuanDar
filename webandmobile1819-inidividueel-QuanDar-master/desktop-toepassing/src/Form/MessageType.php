<?php
/**
 * Created by PhpStorm.
 * User: QuanDar
 * Date: 25/10/2018
 * Time: 08:53
 */

namespace App\Form;

use App\Entity\Category;
use App\Entity\Message;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('id', HiddenType::class, array('label' => false))
            ->add('content', TextType::class, array('required' => false, 'label' => false))
            ->add('date', HiddenType::class, array('required' => false, 'label' => false))
            ->add('upVotes', HiddenType::class, array('required' => false, 'label' => false))
            ->add('downVotes', HiddenType::class, array('required' => false, 'label' => false))
            ->add('categories', CollectionType::class, array(
                'entry_type' => CategoryType::class,
            ))
            //->add('categories', CategoryType::class, array('label' => false))
           //->add('categories', EntityType::class, array(
           //   'class' => Message::class,
           //  'choices' => $this->getCategories()))
            ->add('user', CommentUserType::class, array('required' => false, 'label' => false));

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Message::class,
        ]);
    }
}
