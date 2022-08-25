<?php

namespace App\Controller;

use App\Entity\Ingredient;
use App\Form\IngredientType;
use App\Repository\IngredientRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IngredientController extends AbstractController
{
    #[Route('/ingredient', name: 'app_ingredient')]
    public function index(IngredientRepository $respository): Response //injection de dépéndance
    {
        $ingridients = $respository ->findAll();

   
         return $this->render('pages/ingredient/index.html.twig', [
            'ingredients' =>$ingridients
          
        ]);
    }

    #[Route('/ingredient/nouveau', 'ingredient.new', methods:['GET', 'POST'])]
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

            $manager->persist($ingredient);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre ingrédient a été créé avec succès'

            );
            //return $this->redirectToRoute('ingredient/index.html.twig');
        }



        return $this->render('pages/ingredient/new.html.twig',[
            'form' =>$form->createView()
    ]);


    }

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

    #[Route('/ingredient/suppression/{id}', 'ingredient.delete', methods: ['GET'])]
    public function delete(EntityManagerInterface $manager, Ingredient $ingredient): Response
    {
        if(!$ingredient) {
            $this->addFlash(
                'success',
                'L\'ingrédient introuvable'

            );
        }

        $manager->remove($ingredient);
        $manager->flush();

        return $this->redirectToRoute('ingredient.index');
    }

}
