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

            $entityManager->persist($user);
            $entityManager->flush();

            return new Response('Created user');
            return $this->redirectToRoute('getAllMessages');
        }
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
            $user = $entityManager->getRepository(User::class)->find($user->getId());
            $entityManager->remove($user);
            $entityManager->flush();
        }
    }
}