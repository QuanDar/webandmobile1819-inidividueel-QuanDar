<?php
namespace App\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\User;

class GenerateController extends Controller
{
    /**
     * @Route("/newUser", name="newadminroute")
     */
    public function newUser(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $user = new User();
        $user->setUsername('test');
        $user->setRolesString(
            'ROLE_ADMIN ROLE_USER');
        $password = 'test';
        $encoder = $this->container->
        get('security.password_encoder');
        $encoded = $encoder->encodePassword($user,
            $password);
        $user->setPassword($encoded);
        $em->persist($user);
        $em->flush();
        return new Response('Created user');
    }
}
