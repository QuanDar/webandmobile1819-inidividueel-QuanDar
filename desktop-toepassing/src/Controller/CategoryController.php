<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Form\CommentForm;
use App\Entity\Comment;
use App\Entity\User;
use App\Entity\Message;
use App\Form\DeleteMessageType;
use App\Form\SelectCommentType;
use App\Form\VoteMessageType;
use Doctrine\Common\Collections\ArrayCollection;
use App\Form\MessageType;
use App\Form\MessageSearchType;
use App\Form\CommenType;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Knp\Component\Pager\PaginatorInterface;

class CategoryController extends Controller
{
    private $postStatusCode = 201;

    //Moderator kan alleen categorieen posten

    /**
     * @Route("/category/add", name="addCategory")
     */
    public function postCategory(Request $request)
    {
        $category = new Category();

        $form = $this->createFormBuilder($category)
            ->add('Name', TextType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $category = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($category);
            $entityManager->flush();
//            return $this->redirectToRoute('addCategory');
            return new Response('Category added');
        }

        return $this->render('category/category.html.twig', array(
            'form' => $form->createView(), $this->postStatusCode
        ));
    }
}
