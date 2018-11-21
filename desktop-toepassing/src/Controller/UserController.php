<?php
namespace App\Controller;

use App\Entity\Message;
use App\Entity\Category;
use App\Entity\Comment;
use App\Form\CommenType;
use App\Entity\User;
use App\Form\CommentUserType;
use App\Form\DeleteUserType;
use App\Form\MessageType;
use App\Form\MessageSearchType;
use App\Form\AddUserType;
use App\Form\UpdateUserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Validator\Constraints\DateTime;

class UserController extends Controller
{
    private $okStatusCode = 200;
    private $postStatusCode = 201;
    private $deleteStatusCode = 204;
    /**
     * @Route("/user", name="userindex")
     */
    //Alleen administrator kan moderaters en posters maken
    public function index(Request $request)
    {
        $user = new User();
        $userForm = $this->createForm(AddUserType::class, $user);
        $user = new User();
        $deleteUserForm = $this->createForm(DeleteUserType::class, $user);
        $user = new User();
        $updateUserForm = $this->createForm(UpdateUserType::class, $user);


        return $this->render('user/index.html.twig', array(
            'addUserFormObject' => $userForm,
            'deleteUserFormObject' => $deleteUserForm,
            'updateUserFormObject' => $updateUserForm,
            'controller_name' => 'User Controller'));
    }


    /**
     * @Route("/user/post/add", name="addUser")
     */
    //Alleen administrator kan moderaters en posters maken
    public function postUser(Request $request)
    {
        $user = new User();
        $form = $this->createForm(AddUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $encoder = $this->container->get('security.password_encoder');
            $encoded = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($encoded);
            $user->setEnabled(1);

            $entityManager->persist($user);
            $entityManager->flush();

            return new Response('Created user');
        }
        return new Response('Failed Creating User');
    }

    /**
     * @Route("/user/post/update", name="updateUser")
     */
    //Alleen administrator kan moderaters en posters maken
    public function updateUser(Request $request)
    {
        $user = new User();
        $form = $this->createForm(UpdateUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $userQuery = $entityManager->createQuery(
                'SELECT u
                FROM App\Entity\User u
                WHERE u.username = :username'
            )->setParameter('username', $user->getUsername());
            $dbUser = $userQuery->execute();
            if($dbUser==null){
                return new Response("User not found");
            }
            $updateUser = $dbUser[0];
            $updateUser->setRoles($user->getRoles());
            $user = $updateUser;
            $entityManager->flush();
            return new Response('Updated user');
        }
        return new Response('Failed updating user');

    }

    /**
     * @Route("/user/post/delete", name="deleteUser")
     */
    //Alleen administrator kan moderaters en posters maken
    public function deleteUser(Request $request)
    {
        $user = new User();
        $form = $this->createForm(DeleteUserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $userQuery = $entityManager->createQuery(
                'SELECT u
                FROM App\Entity\User u
                WHERE u.username = :username'
            )->setParameter('username', $user->getUsername());
            $dbUser = $userQuery->execute();
            if($dbUser==null){
                return new Response("User not found");
            }
            $removeUser = $dbUser[0];
            $user = $entityManager->getRepository(User::class)->find($removeUser->getId());
            $entityManager->remove($user);
            $entityManager->flush();
            return new Response('Removed user');
        }
        return new Response('Failed removing user');
    }
}