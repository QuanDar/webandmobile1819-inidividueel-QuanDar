<?php
namespace App\Controller;

use App\Entity\Message;
use App\Entity\Category;
use App\Entity\Comment;
use App\Form\DeleteCommentType;
use App\Form\UpdateCommentType;
use App\Form\UpdateUserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Validator\Constraints\DateTime;

class CommentController extends Controller
{
    /**
     * @Route("/comment", name="commentindex")
     */
    //Alleen administrator kan moderaters en posters maken
    public function index(Request $request)
    {
        $comment = new Comment();
        $updateCommentForm = $this->createForm(UpdateCommentType::class, $comment);
        $deleteCommentForm = $this->createForm(DeleteCommentType::class, $comment);


        return $this->render('comment/index.html.twig', array(
            'updateCommentFormObject' => $updateCommentForm,
            'deleteCommentFormObject' => $deleteCommentForm,));
    }

    /**
     * @Route("/comment/update", name="updateComment")
     */
    public function updateComment(Request $request)
    {
        $comment = new Comment;

        $form = $this->createForm(UpdateCommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $entityManager = $this->getDoctrine()->getManager();
            $commentQuery = $entityManager->createQuery(
                'SELECT c
                FROM App\Entity\Comment c
                WHERE c.id = :id
                AND c.token = :token'
            )->setParameter('id', $comment->getId())
            ->setParameter('token', $comment->getToken());
            $dbComment = $commentQuery->execute();
            if($dbComment==null){
                return new Response("Comment not found");
            }
            $updateComment = $dbComment[0];
            $updateComment->setContent($comment->getContent());
            $comment = $updateComment;
            $entityManager->flush();
            return new Response('Updated Comment');
        }
        return new Response('Failed updating Comment');

    }

    /**
     * @Route("/comment/delete", name="deleteComment")
     */
    public function deleteComment(Request $request)
    {
        $comment = new Comment;

        $form = $this->createForm(DeleteCommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $commentQuery = $entityManager->createQuery(
                'SELECT c
                FROM App\Entity\Comment c
                WHERE c.id = :id
                AND c.token = :token'
            )->setParameter('id', $comment->getId())
                ->setParameter('token', $comment->getToken());
            $dbComment = $commentQuery->execute();
            if($dbComment==null){
                return new Response("Comment not found");
            }
            $removeComment = $dbComment[0];
            $entityManager->remove($removeComment);
            $entityManager->flush();
            return new Response('Deleted Comment');
        }
        return new Response('Failed deleting Comment');
    }
}