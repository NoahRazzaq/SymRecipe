<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Controller\IngredientType;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RecipeController extends AbstractController
{
    #[IsGranted('ROLE_USER')]
    #[Route('/recette', name: 'recipe.index', methods:['GET'])]
    public function index(RecipeRepository $respository, Request $request ): Response
    {
        $recipes = $respository ->findBy(['user' =>$this->getUser()]);

        return $this->render('pages/recipe/index.html.twig', [
            'recipes' => $recipes,
        ]);
    }

    #[Route('/recette/creation', 'recipe.new', methods:['GET', 'POST'])]
    #[IsGranted('ROLE_USER')]
    public function new (Request $request, EntityManagerInterface $manager): Response
    {
        $recipe = new Recipe();
        $form = $this->createForm(RecipeType::class, $recipe);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            
            $recipe = $form->getData();
            $recipe->setUser($this->getUser());

            $manager->persist($recipe);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre recette a été créé avec succès'

            );

            return $this->redirectToRoute('recipe.index');
        }

        return $this->render('pages/recipe/new.html.twig', [
            'form' =>$form->createView()
        ]);
    }
    #[Security("is_granted('ROLE_USER)and user === recipe.getUser() ")]
    #[Route('/recette/edition/{id}', 'recipe.edit', methods:['GET','POST'])]
    public function edit(RecipeRepository $respository,
                         Recipe $recipe,
                          Request $request,
                           EntityManagerInterface $manager): Response
    {
       
        $form = $this->createForm(RecipeType::class, $recipe);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $recipe = $form->getData();

            $manager->persist($recipe);
            $manager->flush();

            $this->addFlash(
                'success',
                'Votre recette a été modifié avec success'

            );

            return $this->redirectToRoute('ingredient.index');

        }

        return $this->render('pages/ingredient/edit.html.twig',[
            'form' => $form->createView()
        ]);
    }

    #[Route('/recette/suppression/{id}', 'recipe.delete', methods: ['GET'])]
    public function delete(EntityManagerInterface $manager, Recipe $recipe): Response
    {
        if(!$recipe) {
            $this->addFlash(
                'success',
                'La recette est introuvable'

            );
        }

        $manager->remove($recipe);
        $manager->flush();

        return $this->redirectToRoute('recipe.index');
    }
}
