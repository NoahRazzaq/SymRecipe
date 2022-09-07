<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Form\IngredientType;
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IngredientController extends AbstractController
{
    #[Route('/ingredient', 'ingredient.index')]
    #[IsGranted('ROLE_USER')]
    public function index(IngredientRepository $respository): Response //injection de dépéndance
    {
        $ingridients = $respository ->findBy(['user'=> $this->getUser()]);

   
         return $this->render('pages/ingredient/index.html.twig', [
            'ingredients' =>$ingridients
          
        ]);
    }

    #[Route('/ingredient/creation', 'ingredient.new', methods:['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function new(
        Request $request,
        EntityManagerInterface $manager
        ):Response

    {
        $ingredient = new Ingredient();
        $form = $this->createForm(IngredientType::class, $ingredient);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $ingredient = $form->getData();
            $ingredient->setUser($this->getUser());

            $manager->persist($ingredient);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre ingrédient a été créé avec succès'

            );
            return $this->redirectToRoute('ingredient.index');
        }



        return $this->render('pages/ingredient/new.html.twig',[
            'form' =>$form->createView()
    ]);


    }
    #[Security("is_granted('ROLE_USER') and user === ingredient.getUser()")]
    #[Route('/ingredient/edition/{id}', 'ingredient.edit', methods:['GET','POST'])]
    public function edit(IngredientRepository $respository,
                         Ingredient $ingredient,
                          Request $request,
                           EntityManagerInterface $manager): Response
    {
       
        $form = $this->createForm(IngredientType::class, $ingredient);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $ingredient = $form->getData();

            $manager->persist($ingredient);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre ingrédient a été modifié avec success'
            );

            return $this->redirectToRoute('ingredient.index');

        }

        return $this->render('pages/ingredient/edit.html.twig',[
            'form' => $form->createView()
        ]);
    }

    #[Security("is_granted('ROLE_USER') and user === ingredient.getUser()")]
    #[Route('/ingredient/suppression/{id}', 'ingredient.delete', methods: ['GET'])]
    public function delete(EntityManagerInterface $manager, Ingredient $ingredient): Response
    {
        $this->addFlash(
            'success',
            'Votre ingrédient a été supprimé avec succès !'
        );
        $manager->remove($ingredient);
        $manager->flush();

        return $this->redirectToRoute('ingredient.index');
    }

}
