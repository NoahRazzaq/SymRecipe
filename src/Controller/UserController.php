<?php

namespace App\Controller;

use App\Form\UserType;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends AbstractController
{
    #[Security("is_granted('ROLE_USER') and user === choosenUser")]
    #[Route('/utilisateur/edition/{id}', name: 'user.edit')]
    public function index(User $choosenUser,
                         Request $request,
                         EntityManagerInterface $manager,
                         UserPasswordHasherInterface $hasher): Response
    { 
        $form = $this->createForm(UserType::class, $choosenUser);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {

            if($hasher->isPasswordValid($choosenUser, $form->getData()->getPlainPassword())) //on cherche le password
            {
                $choosenUser = $form->getData();
                $manager->persist($choosenUser);
                $manager->flush();
    
                $this->addFlash(
                    'success',
                    'Les informations de votre compte ont bien été modifiés.'  
                );
                return $this->redirectToRoute('recipe.index');

            }
            else
            {
                $this->addFlash(
                    'warning',
                    'Le mot de passe est incorrect.'  
                );
            }
        }
        return $this->render('pages/user/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
