<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\RegistartionType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/inscription", name="inscription")
     */
    public function inscription(Request $request ,UserPasswordEncoderInterface $encoder): Response
    {
        $user = new Users();
        $form = $this->createForm(RegistartionType::class,$user);
        $form->handleRequest($request); 
        if ($form->isSubmitted() && $form->isValid()) {
            $hash = $encoder->encodePassword($user, $user->getPassword());
            $user->setPassword($hash);
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();
            
        }
        return $this->render('security/inscription.html.twig',[
            'form' => $form->createView()]);
    }

    /**
     * @Route("/connexion", name="security_login")
     */
    public function login()
    {
        return $this->render('security/login.html.twig');
    }


    /**
     * @Route("/deconnexion", name="security_logout")
     */
    public function logout()
    {
        
    }

    /**
     * @Route("/", name="welcome")
     */
    public function welcome()
    {
       
        $user = new Users() ;
        $user = $this->getUser();
        return $this->redirectToRoute('profile', ['id' => $user->getId()]);

    }

    /**
     * @Route("/profile/{id}", name="profile")
     */
    public function profile($id)
    {
        $user = new Users();
        $user = $this->getUser();
        return $this->render('user/connected.html.twig',['user'=>$user]);
        
    }

    /**
     * @Route("/blog", name="blog")
     */
    public function accueil()
    {
        return $this->render('user/desconnected.html.twig');
    }
}
