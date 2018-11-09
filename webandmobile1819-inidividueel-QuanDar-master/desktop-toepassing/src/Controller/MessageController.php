<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryType;
use App\Form\CommentForm;
use App\Entity\Comment;
use App\Entity\User;
use App\Entity\Message;
use App\Form\DeleteMessageType;
use App\Form\VoteMessageType;
use Doctrine\Common\Collections\ArrayCollection;
use App\Form\MessageType;
use App\Form\MessageSearchType;
use App\Form\CommenType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Knp\Component\Pager\PaginatorInterface;

class MessageController extends Controller
{
    // Rechten:
    // Als ik praat over een poster, dan heeft de modarator natuurlijk ook de rechten om die functie uit te voeren
    // maar een anonieme gebruiker niet.

    // moderator only
    public function deleteAllMessagesFromPoster(string $user)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $messages = $entityManager->getRepository('appBundle:Message')->findBy(array('name' => $user));
        if (!$messages)
        {
            throw $this->createNotFoundException(
                'No messages found for user ' .$user
            );
        }
        foreach ($messages as $message)
        {
            $entityManager->remove($message);
        }
        $entityManager->flush();
    }

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
            return $this->redirectToRoute('addCategory');
        }

        return $this->render('category/category.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function getCategories()
    {
        $categories = $this->getDoctrine()->getManager()->getRepository(Category::class)->findAll();
        return $categories;
    }

    // Anonieme gebruikers kunnen zoeken in messages
    /**
     * @Route("/message/find/{id}", name="getMessageById")
     */
    public function getMessage(Request $request)
    {
        $id=$request->get("id");
        $message = $this->getDoctrine()->getManager()->getRepository(Message::class)->find($id);
        return new Response($message);
    }

    // anonieme gebruikers
    // we moeten gebruik maken van paginatie.
    /**
     * @Route("/message/getAll", name="getAllMessages")
     */
    public function getMessages(Request $request, PaginatorInterface $paginator)
    {
        $comment = new Comment();
        $commentForm = $this->createForm(CommenType::class, $comment);
        // Form for creating searched message
        $searchMessage = new Message();
        $messageSearchForm = $this->createForm(MessageSearchType::class, $searchMessage);

        $message = new Message();
        $messageForm =  $this->createForm(MessageType::class, $message);
        $deleteMessageForm =  $this->createForm(DeleteMessageType::class, $message);
        $upVoteMessageForm =  $this->createForm(VoteMessageType::class, $message);
        $downVoteMessageForm =  $this->createForm(VoteMessageType::class, $message);

        $category = new Category();
        $categoryForm = $this->createForm(CategoryType::class, $category);

        // form for retrieving search message query
        $searchedMessage = new Message();
        $form = $this->createForm(MessageSearchType::class, $searchedMessage);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $messagesRepository = $this->getDoctrine()->getManager()->getRepository(Message::class);
            $content = $searchedMessage->getContent();
            $queryBuilder = $messagesRepository->createQueryBuilder('m')->where('m.content LIKE :content')->setParameter('content', "%$content%")->getQuery();
        } else {
            $messagesRepository = $this->getDoctrine()->getManager()->getRepository(Message::class);
            $queryBuilder = $messagesRepository->createQueryBuilder('p')->getQuery();
        }
        //paginatie
        $pagination = $paginator->paginate(
            $queryBuilder,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('message/index.html.twig', array(
            'messageSearchFormObject' => $messageSearchForm,
            'commentFormObject' => $commentForm,
            'messageFormObject' => $messageForm,
            'categoryFormObject' => $categoryForm,
            'deleteMessageFormObject' => $deleteMessageForm,
            'upVoteMessageFormObject' => $upVoteMessageForm,
            'downVoteMessageFormObject' => $downVoteMessageForm,
            'messages' => $pagination,
            'controller_name' => 'Message Controller'));
    }

    // Anonieme gebruikers kunnen zoeken in messages
    /**
     * @Route( name="searchMessages")
     */
    public function searchMessages(Request $request, PaginatorInterface $paginator){

    }

    // posters kunnen berichten aanmaken in bestaande categorie
    /**
     * @Route("/message/post", name="postMessage")
     */
    public function postMessage(Request $request)
    {
        $message = new Message();
        $form = $this->createForm(MessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $message->setDate(new \DateTime());
            $message->setDownVotes(0);
            $message->setUpVotes(0);

            if($message->getUser() != null) {
                $message->setUser($this->getDoctrine()->getManager()->getRepository(User::class)->find($message->getUser()->getId()));
            } else {
                return $this->redirectToRoute('loginroute');
            }
            $entityManager->persist($message);
            $entityManager->flush();

            return $this->redirectToRoute('getAllMessages');
        }
    }

    // poster kan alleen eigen message updaten
    public function updateMessage(Request $request)
    {
        $user = new User();
        $form = $this->createForm(MessageType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $updatedUser = $entityManager->createQuery(
                'SELECT u
                FROM App\Entity\User u
                WHERE u.userName = :userName'
            )->setParameter('userName', $user->getUsername());
            $updatedUser->setUserName($user->getUsername());

            $encoder = $this->container->get('security.password_encoder');
            $encoded = $encoder->encodePassword($user, $user->getPassword());

            if($encoded != $updatedUser->getPassword()){
                $updatedUser->setPassword($encoded);
            }
            $updatedUser->setRolesString($user->getRolesString());

            $entityManager->flush();
            return new Response('Updated user');
        }
        return new Response('Failed updating user');

    }

    /**
     * @Route("/message/downVoteMessage", name="downVoteMessage")
     */
    public function downVoteMessage(Request $request){
        $message = new Message();
        $form = $this->createForm(VoteMessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $message = $this->getDoctrine()->getManager()->getRepository(Message::class)->find($message->getId());
            $message->setDownVotes($message->getDownVotes() + 1);

            $entityManager->flush();
        }
        return $this->redirectToRoute('getAllMessages');
    }

    /**
     * @Route("/message/upVoteMessage", name="upVoteMessage")
     */
    public function upVoteMessage(Request $request){
        $message = new Message();
        $form = $this->createForm(VoteMessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $message = $this->getDoctrine()->getManager()->getRepository(Message::class)->find($message->getId());
            $message->setUpVotes($message->getUpVotes() + 1);

            $entityManager->flush();
        }
        return $this->redirectToRoute('getAllMessages');
    }

    // poster kan alleen eigen message deleten
    /**
     * @Route("/message/delete", name="deleteMessage")
     */
    public function deleteMessage(Request $request)
    {
        $message = new Message();
        $form = $this->createForm(DeleteMessageType::class, $message);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $message = $this->getDoctrine()->getManager()->getRepository(Message::class)->find($message->getId());
            $entityManager->remove($message);
            $entityManager->flush();
        }
        return $this->redirectToRoute('getAllMessages');
    }

    // anoniem
    // link naar message
    // Bij het aanmaken krijgt de gebruiker het token en id van de reactie.
    /**
     * @Route("/message/comment/post", name="formComment")
     */
    public function postComment(Request $request)
    {
        $comment = new Comment();
        $form = $this->createForm(CommenType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $comment->setDate(new \DateTime());
            if($comment->getUser() != null){
                $comment->setUser($this->getDoctrine()->getManager()->getRepository(User::class)->find($comment->getUser()->getId()));
            }
            $comment->setMessage($this->getDoctrine()->getManager()->getRepository(Message::class)->find($comment->getMessage()->getId()));


            $entityManager->persist($comment);
            $entityManager->flush();

            return $this->redirectToRoute('getAllMessages');
        }
    }

    //De gebruiker kan wijzigen en verwijderen adhv het
    //token dat hoort bij het bericht 1pt
    public function updateComment(int $id, string $newContent)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $comment =  $entityManager->getRepository(Comment::class)->find($id);
        if (!$comment)
        {
            throw $this->createNotFoundException(
                'No comment found for id ' . $id
            );
        }
        $comment->setContent($newContent);
        $entityManager->flush();
    }

    //De gebruiker kan wijzigen en verwijderen adhv het
    //token dat hoort bij het bericht 1pt
    public function deleteComment(int $id)
    {
        $entityManager = $this->getDoctrine()->getManager();
        $comment = $entityManager->getRepository('appBundle:Comment')->find($id);
        if (!$comment)
        {
            throw $this->createNotFoundException(
                'No comment found for id '.$id
            );
        }
        $entityManager->remove($comment);
        $entityManager->flush();
    }
}
